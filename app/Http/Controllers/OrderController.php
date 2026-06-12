<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\BarangKeluarItem;
use App\Models\Customer;
use App\Models\DataBarang;
use App\Models\InventoryLog;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Pengiriman;
use App\Models\StockReservation;
use App\Models\User;
use App\Services\NotificationService;
use App\Services\StockMovementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function __construct(
        private readonly NotificationService $notificationService,
        private readonly StockMovementService $stockMovementService
    ) {}

    public function index(Request $request)
    {
        abort_unless(
            auth()->check() && (
                auth()->user()->canManageSales() ||
                auth()->user()->canManageWarehouse() ||
                auth()->user()->canManageShipping()
            ),
            403
        );

        $keyword = trim((string) $request->query('q', ''));
        $status = trim((string) $request->query('status', ''));

        $orders = Order::with(['customer', 'items.dataBarang', 'pengiriman', 'stockReservations'])
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where('nomor_pesanan', 'like', '%'.$keyword.'%')
                    ->orWhereHas('customer', function ($cq) use ($keyword) {
                        $cq->where('nama_customer', 'like', '%'.$keyword.'%');
                    });
            })
            ->when($status !== '', function ($query) use ($status) {
                $query->where('status_pesanan', $status);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $customers = Customer::where('status', 'aktif')->orderBy('nama_customer')->get();
        $barangs = DataBarang::orderBy('nama_barang')->get();

        return view('admin.orders', compact('orders', 'customers', 'barangs', 'status'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->check() && auth()->user()->canManageSales(), 403);

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'catatan' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.data_barang_id' => 'required|exists:data_barangs,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.catatan' => 'nullable|string|max:255',
        ]);

        $user = auth()->user();
        $order = null;

        DB::transaction(function () use ($validated, $user, &$order) {
            $order = Order::create([
                'nomor_pesanan' => $this->generateOrderNumber(),
                'customer_id' => $validated['customer_id'],
                'tanggal_pesanan' => now(),
                'status_pesanan' => 'pending',
                'order_source' => 'admin_manual',
                'catatan' => $validated['catatan'] ?? null,
                'created_by' => $user->id,
            ]);

            $totalQty = 0;
            $totalNilai = 0;
            foreach ($validated['items'] as $item) {
                $barang = DataBarang::findOrFail($item['data_barang_id']);
                $hargaJual = (float) ($barang->harga_jual ?? $barang->harga ?? 0);
                $qty = (int) $item['qty'];
                $subtotal = $hargaJual * $qty;

                OrderItem::create([
                    'order_id' => $order->id,
                    'data_barang_id' => $barang->id,
                    'qty' => $qty,
                    'harga_jual_snapshot' => $hargaJual,
                    'subtotal' => $subtotal,
                    'catatan' => $item['catatan'] ?? null,
                ]);

                $totalQty += $qty;
                $totalNilai += $subtotal;
            }

            $order->update([
                'total_item' => count($validated['items']),
                'total_qty' => $totalQty,
                'total_nilai' => $totalNilai,
            ]);

            $this->logAction($user->id, null, 'order_create', [
                'order_id' => $order->id,
                'nomor_pesanan' => $order->nomor_pesanan,
            ]);

            $this->reserveOrderStock($order, $user->id, true);

            $this->notificationService->notifyUser(
                (int) optional($order->customer)->user_id,
                'Pesanan Dibuat',
                'Pesanan '.$order->nomor_pesanan.' berhasil dibuat dan sedang diverifikasi.',
                'order',
                'order',
                $order->id
            );
        });

        return back()->with('success', 'Pesanan berhasil dibuat. Status: '.strtoupper((string) $order->fresh()->status_pesanan));
    }

    public function approve(Order $order)
    {
        abort_unless(auth()->check() && auth()->user()->canManageSales(), 403);

        DB::transaction(function () use ($order) {
            $this->reserveOrderStock($order->fresh(['items']), auth()->id(), false);
        });

        return back()->with('success', 'Pesanan diproses untuk reservasi stok.');
    }

    public function reject(Request $request, Order $order)
    {
        abort_unless(auth()->check() && auth()->user()->canManageSales(), 403);

        if (in_array($order->status_pesanan, ['shipped', 'completed'], true)) {
            return back()->withErrors(['status' => 'Pesanan sudah berjalan dan tidak bisa ditolak.']);
        }

        $note = trim((string) $request->input('catatan_tolak', ''));

        DB::transaction(function () use ($order, $note) {
            $this->releaseReservations($order, auth()->id(), 'order_reject');

            $order->update([
                'status_pesanan' => 'rejected',
                'catatan' => $note !== '' ? $note : $order->catatan,
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);
        });

        $this->logAction(auth()->id(), null, 'order_reject', [
            'order_id' => $order->id,
            'nomor_pesanan' => $order->nomor_pesanan,
            'catatan' => $note,
        ]);

        $this->notifyOrderStatus($order->fresh(), 'Pesanan Ditolak', 'Pesanan ditolak oleh tim penjualan.');

        return back()->with('success', 'Pesanan ditolak.');
    }

    public function destroy(Order $order)
    {
        abort_unless(auth()->check() && auth()->user()->canManageSales(), 403);

        if ($order->status_pesanan === Order::STATUS_COMPLETED) {
            return back()->withErrors(['status' => 'Pesanan yang sudah selesai tidak dapat dihapus.']);
        }

        $nomorPesanan = $order->nomor_pesanan;

        DB::transaction(function () use ($order) {
            $lockedOrder = Order::with(['items.dataBarang', 'barangKeluar.items.dataBarang'])
                ->lockForUpdate()
                ->findOrFail($order->id);

            if ($lockedOrder->status_pesanan === Order::STATUS_COMPLETED) {
                abort(422, 'Pesanan yang sudah selesai tidak dapat dihapus.');
            }

            $barangKeluar = $lockedOrder->barangKeluar;
            if ($barangKeluar && $barangKeluar->items->isNotEmpty()) {
                foreach ($barangKeluar->items as $keluarItem) {
                    $barang = DataBarang::lockForUpdate()->findOrFail($keluarItem->data_barang_id);
                    $qtyRestore = (int) $keluarItem->qty_keluar;
                    if ($qtyRestore <= 0) {
                        continue;
                    }

                    $stokSebelum = (int) $barang->stok;
                    $stokSesudah = $stokSebelum + $qtyRestore;
                    $barang->update(['stok' => $stokSesudah]);

                    $this->logAction(auth()->id(), $barang->id, 'stock_in', [
                        'order_id' => $lockedOrder->id,
                        'nomor_pesanan' => $lockedOrder->nomor_pesanan,
                        'qty_masuk' => $qtyRestore,
                        'stok_sebelum' => $stokSebelum,
                        'stok_sesudah' => $stokSesudah,
                        'source' => 'order_delete',
                    ]);

                    $this->stockMovementService->record([
                        'data_barang_id' => $barang->id,
                        'order_id' => $lockedOrder->id,
                        'movement_type' => 'stock_in_order_delete',
                        'qty_change' => $qtyRestore,
                        'qty_before' => $stokSebelum,
                        'qty_after' => $stokSesudah,
                        'reference_type' => 'order',
                        'reference_id' => $lockedOrder->id,
                        'notes' => 'Stok dikembalikan karena hapus order '.$lockedOrder->nomor_pesanan,
                        'created_by' => auth()->id(),
                    ]);
                }
            }

            if ($barangKeluar) {
                $barangKeluar->delete();
            }

            $orderId = $lockedOrder->id;
            $orderNumber = $lockedOrder->nomor_pesanan;
            $lockedOrder->delete();

            $this->logAction(auth()->id(), null, 'order_delete', [
                'order_id' => $orderId,
                'nomor_pesanan' => $orderNumber,
                'deleted_by' => auth()->id(),
            ]);
        });

        return back()->with('success', 'Pesanan '.$nomorPesanan.' berhasil dihapus.');
    }

    public function verifyPayment(Order $order)
    {
        abort_unless(auth()->check() && auth()->user()->canManageSales(), 403);

        if ((string) ($order->payment_status ?? Order::PAYMENT_UNPAID) !== Order::PAYMENT_AWAITING_VERIFICATION) {
            return back()->withErrors(['payment' => 'Status pembayaran tidak dalam antrian verifikasi.']);
        }

        if (! filled($order->payment_proof_path)) {
            return back()->withErrors(['payment' => 'Bukti pembayaran belum ada.']);
        }

        $order->update([
            'payment_status' => Order::PAYMENT_VERIFIED,
            'payment_verified_by' => auth()->id(),
            'payment_verified_at' => now(),
            'payment_reject_note' => null,
        ]);

        $this->logAction(auth()->id(), null, 'order_payment_verified', [
            'order_id' => $order->id,
            'nomor_pesanan' => $order->nomor_pesanan,
        ]);

        $customerUserId = (int) optional($order->customer)->user_id;
        if ($customerUserId > 0) {
            $this->notificationService->notifyUser(
                $customerUserId,
                'Pembayaran Diverifikasi',
                'Pembayaran untuk order '.$order->nomor_pesanan.' sudah diverifikasi.',
                'order',
                'order',
                $order->id
            );
        }

        return back()->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    public function rejectPayment(Request $request, Order $order)
    {
        abort_unless(auth()->check() && auth()->user()->canManageSales(), 403);

        $validated = $request->validate([
            'payment_reject_note' => 'required|string|max:500',
        ]);

        if (! in_array((string) ($order->payment_status ?? Order::PAYMENT_UNPAID), [Order::PAYMENT_AWAITING_VERIFICATION, Order::PAYMENT_VERIFIED], true)) {
            return back()->withErrors(['payment' => 'Pembayaran belum bisa ditolak untuk status ini.']);
        }

        $note = trim((string) $validated['payment_reject_note']);
        $order->update([
            'payment_status' => Order::PAYMENT_REJECTED,
            'payment_verified_by' => auth()->id(),
            'payment_verified_at' => now(),
            'payment_reject_note' => $note,
        ]);

        $this->logAction(auth()->id(), null, 'order_payment_rejected', [
            'order_id' => $order->id,
            'nomor_pesanan' => $order->nomor_pesanan,
            'reason' => $note,
        ]);

        $customerUserId = (int) optional($order->customer)->user_id;
        if ($customerUserId > 0) {
            $this->notificationService->notifyUser(
                $customerUserId,
                'Pembayaran Ditolak',
                'Pembayaran order '.$order->nomor_pesanan.' ditolak. Alasan: '.$note,
                'order',
                'order',
                $order->id
            );
        }

        return back()->with('success', 'Pembayaran ditolak dan customer telah diberi notifikasi.');
    }

    public function viewPaymentProof(Order $order)
    {
        abort_unless(auth()->check() && auth()->user()->canManageSales(), 403);

        $proofPath = $this->normalizePaymentProofPath((string) ($order->payment_proof_path ?? ''));
        if ($proofPath === '' || ! Storage::disk('public')->exists($proofPath)) {
            abort(404, 'Bukti pembayaran tidak ditemukan.');
        }

        return response()->file(Storage::disk('public')->path($proofPath));
    }

    public function startPicking(Order $order)
    {
        abort_unless(auth()->check() && auth()->user()->canManageWarehouse(), 403);

        if (! in_array($order->status_pesanan, ['reserved', 'approved'], true)) {
            return back()->withErrors(['status' => 'Pesanan belum dalam status siap picking.']);
        }

        $order->update(['status_pesanan' => 'picking']);

        $this->logAction(auth()->id(), null, 'order_picking', [
            'order_id' => $order->id,
            'nomor_pesanan' => $order->nomor_pesanan,
        ]);

        $this->notifyOrderStatus($order->fresh(), 'Pesanan Dipicking', 'Pesanan sedang disiapkan oleh tim gudang.');

        return back()->with('success', 'Pesanan masuk proses picking.');
    }

    public function markPacked(Order $order)
    {
        abort_unless(auth()->check() && auth()->user()->canManageWarehouse(), 403);

        DB::transaction(function () use ($order) {
            $order = Order::with(['items', 'customer', 'stockReservations'])
                ->lockForUpdate()
                ->findOrFail($order->id);

            if (! in_array($order->status_pesanan, ['reserved', 'picking', 'approved'], true)) {
                abort(422, 'Pesanan belum bisa dipacking.');
            }

            if ($order->stockReservations()->where('status', 'active')->count() === 0) {
                $this->reserveOrderStock($order, auth()->id(), false);
                $order->refresh();
                if ($order->status_pesanan === 'waiting_restock') {
                    abort(422, 'Stok belum cukup untuk proses packing.');
                }
            }

            $barangKeluar = BarangKeluar::firstOrCreate(
                ['order_id' => $order->id],
                [
                    'nomor_barang_keluar' => $this->generateBarangKeluarNumber(),
                    'tanggal_keluar' => now(),
                    'status' => 'diproses',
                    'user_id' => auth()->id(),
                    'catatan' => 'Packing dari pesanan '.$order->nomor_pesanan,
                ]
            );

            foreach ($order->items as $item) {
                $reservedQty = (int) StockReservation::where('order_id', $order->id)
                    ->where('order_item_id', $item->id)
                    ->where('status', 'active')
                    ->sum('qty_reserved');

                $qtyToDeduct = $reservedQty > 0 ? $reservedQty : (int) $item->qty;
                $barang = DataBarang::lockForUpdate()->findOrFail($item->data_barang_id);

                if ((int) $barang->stok < $qtyToDeduct) {
                    abort(422, 'Stok barang '.$barang->nama_barang.' tidak cukup saat packing.');
                }

                $stokSebelum = (int) $barang->stok;
                $stokSesudah = $stokSebelum - $qtyToDeduct;
                $barang->update(['stok' => $stokSesudah]);

                BarangKeluarItem::create([
                    'barang_keluar_id' => $barangKeluar->id,
                    'data_barang_id' => $barang->id,
                    'qty_keluar' => $qtyToDeduct,
                    'stok_sebelum' => $stokSebelum,
                    'stok_sesudah' => $stokSesudah,
                    'harga_snapshot' => (float) $item->harga_jual_snapshot,
                    'subtotal' => (float) $item->subtotal,
                ]);

                StockReservation::where('order_id', $order->id)
                    ->where('order_item_id', $item->id)
                    ->where('status', 'active')
                    ->update([
                        'status' => 'fulfilled',
                        'fulfilled_at' => now(),
                    ]);

                $this->logAction(auth()->id(), $barang->id, 'stock_out', [
                    'order_id' => $order->id,
                    'nomor_pesanan' => $order->nomor_pesanan,
                    'qty_keluar' => $qtyToDeduct,
                    'stok_sebelum' => $stokSebelum,
                    'stok_sesudah' => $stokSesudah,
                    'source' => 'packing',
                ]);

                $this->stockMovementService->record([
                    'data_barang_id' => $barang->id,
                    'order_id' => $order->id,
                    'movement_type' => 'stock_out_order',
                    'qty_change' => -$qtyToDeduct,
                    'qty_before' => $stokSebelum,
                    'qty_after' => $stokSesudah,
                    'reference_type' => 'order',
                    'reference_id' => $order->id,
                    'notes' => 'Pengurangan stok karena packing order '.$order->nomor_pesanan,
                    'created_by' => auth()->id(),
                ]);
            }

            Pengiriman::firstOrCreate(
                ['order_id' => $order->id],
                [
                    'barang_keluar_id' => $barangKeluar->id,
                    'status_pengiriman' => 'siap_pickup',
                    'alamat_kirim_snapshot' => optional($order->customer)->alamat,
                    'penerima' => optional($order->customer)->nama_customer,
                ]
            );

            $order->update([
                'status_pesanan' => 'packed',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            $this->logAction(auth()->id(), null, 'order_packed', [
                'order_id' => $order->id,
                'nomor_pesanan' => $order->nomor_pesanan,
                'barang_keluar_id' => $barangKeluar->id,
            ]);
        });

        $this->notifyOrderStatus($order->fresh(), 'Pesanan Siap Dikirim', 'Pesanan selesai dipacking dan menunggu pengiriman.');

        return back()->with('success', 'Pesanan selesai dipacking dan stok terpotong.');
    }

    public function markShipped(Request $request, Order $order)
    {
        abort_unless(auth()->check() && auth()->user()->canManageShipping(), 403);

        if (! in_array($order->status_pesanan, ['packed'], true)) {
            return back()->withErrors(['status' => 'Pesanan harus berstatus PACKED sebelum dikirim.']);
        }

        $validated = $request->validate([
            'metode_kirim' => 'required|string|max:100',
            'nama_ekspedisi' => 'required|string|max:100',
            'no_resi' => 'required|string|max:100',
            'biaya_kirim' => 'nullable|numeric|min:0',
        ]);

        $pengiriman = $order->pengiriman;
        if (! $pengiriman) {
            $pengiriman = Pengiriman::create([
                'order_id' => $order->id,
                'barang_keluar_id' => optional($order->barangKeluar)->id,
                'status_pengiriman' => 'siap_pickup',
            ]);
        }

        $pengiriman->update([
            'metode_kirim' => $validated['metode_kirim'],
            'nama_ekspedisi' => $validated['nama_ekspedisi'],
            'no_resi' => $validated['no_resi'],
            'biaya_kirim' => (float) ($validated['biaya_kirim'] ?? 0),
            'status_pengiriman' => 'dikirim',
            'tanggal_kirim' => now(),
        ]);

        $order->update(['status_pesanan' => 'shipped']);

        $this->logAction(auth()->id(), null, 'order_ship', [
            'order_id' => $order->id,
            'nomor_pesanan' => $order->nomor_pesanan,
            'no_resi' => $pengiriman->no_resi,
            'ekspedisi' => $pengiriman->nama_ekspedisi,
        ]);

        $this->notifyOrderStatus($order->fresh(), 'Pesanan Dikirim', 'Pesanan sedang dalam proses pengiriman.');

        return back()->with('success', 'Pesanan ditandai sudah dikirim.');
    }

    public function markDelivered(Order $order)
    {
        abort_unless(auth()->check() && auth()->user()->canManageShipping(), 403);

        if ($order->status_pesanan !== 'shipped') {
            return back()->withErrors(['status' => 'Pesanan belum dalam status dikirim.']);
        }

        $pengiriman = $order->pengiriman;
        if ($pengiriman) {
            $pengiriman->update([
                'status_pengiriman' => 'terkirim',
                'tanggal_terima' => now(),
            ]);
        }

        $order->update(['status_pesanan' => 'completed']);

        $this->logAction(auth()->id(), null, 'order_complete', [
            'order_id' => $order->id,
            'nomor_pesanan' => $order->nomor_pesanan,
        ]);

        $this->notifyOrderStatus($order->fresh(), 'Pesanan Selesai', 'Pesanan telah diterima. Terima kasih.');

        return back()->with('success', 'Pesanan selesai.');
    }

    private function reserveOrderStock(Order $order, int $userId, bool $auto): void
    {
        $order = Order::with(['items'])->lockForUpdate()->findOrFail($order->id);

        if (in_array($order->status_pesanan, ['packed', 'shipped', 'completed', 'rejected'], true)) {
            return;
        }

        $this->releaseReservations($order, $userId, 're_reserve', false);

        $insufficient = [];
        $reservableItems = [];

        foreach ($order->items as $item) {
            $barang = DataBarang::lockForUpdate()->findOrFail($item->data_barang_id);
            $qty = (int) $item->qty;
            $availableStock = $this->calculateAvailableStock($barang->id, $order->id);

            if ($availableStock < $qty) {
                $insufficient[] = [
                    'data_barang_id' => $barang->id,
                    'nama_barang' => $barang->nama_barang,
                    'stok_fisik' => (int) $barang->stok,
                    'stok_tersedia' => $availableStock,
                    'qty_pesanan' => $qty,
                ];

                continue;
            }

            $reservableItems[] = [$item, $barang, $qty];
        }

        if (! empty($insufficient)) {
            $order->update([
                'status_pesanan' => 'waiting_restock',
                'approved_by' => $userId,
                'approved_at' => now(),
            ]);

            $this->logAction($userId, null, 'order_waiting_restock', [
                'order_id' => $order->id,
                'nomor_pesanan' => $order->nomor_pesanan,
                'insufficient' => $insufficient,
                'auto' => $auto,
            ]);

            $this->notifyOrderStatus($order->fresh(), 'Menunggu Restock', 'Beberapa item belum tersedia. Pesanan menunggu restock.');

            return;
        }

        foreach ($reservableItems as [$item, $barang, $qty]) {
            StockReservation::create([
                'order_id' => $order->id,
                'order_item_id' => $item->id,
                'data_barang_id' => $barang->id,
                'qty_reserved' => $qty,
                'status' => 'active',
                'reserved_by' => $userId,
                'reserved_at' => now(),
                'notes' => 'Reservasi dari pesanan '.$order->nomor_pesanan,
            ]);
        }

        $order->update([
            'status_pesanan' => 'reserved',
            'approved_by' => $userId,
            'approved_at' => now(),
        ]);

        $this->logAction($userId, null, 'order_reserved', [
            'order_id' => $order->id,
            'nomor_pesanan' => $order->nomor_pesanan,
            'auto' => $auto,
        ]);

        $this->notifyOrderStatus($order->fresh(), 'Stok Direservasi', 'Pesanan sudah tervalidasi dan stok berhasil direservasi.');
    }

    private function calculateAvailableStock(int $dataBarangId, ?int $excludeOrderId = null): int
    {
        $physical = (int) DataBarang::whereKey($dataBarangId)->value('stok');
        $reservedQuery = StockReservation::where('data_barang_id', $dataBarangId)
            ->where('status', 'active');

        if ($excludeOrderId !== null) {
            $reservedQuery->where('order_id', '!=', $excludeOrderId);
        }

        $reserved = (int) $reservedQuery->sum('qty_reserved');

        return max(0, $physical - $reserved);
    }

    private function releaseReservations(Order $order, int $userId, string $reason, bool $withLog = true): int
    {
        $affected = StockReservation::where('order_id', $order->id)
            ->where('status', 'active')
            ->update([
                'status' => 'released',
                'released_at' => now(),
                'notes' => $reason,
            ]);

        if ($withLog && $affected > 0) {
            $this->logAction($userId, null, 'order_reservation_released', [
                'order_id' => $order->id,
                'nomor_pesanan' => $order->nomor_pesanan,
                'qty_rows' => $affected,
                'reason' => $reason,
            ]);
        }

        return $affected;
    }

    private function logAction(?int $userId, ?int $dataBarangId, string $action, array $changes = []): void
    {
        InventoryLog::create([
            'user_id' => $userId,
            'data_barang_id' => $dataBarangId,
            'action' => $action,
            'changes' => $changes,
        ]);
    }

    private function generateOrderNumber(): string
    {
        return 'ORD-'.now()->format('Ymd-His').'-'.strtoupper(substr(md5((string) microtime(true)), 0, 4));
    }

    private function generateBarangKeluarNumber(): string
    {
        return 'BK-'.now()->format('Ymd-His').'-'.strtoupper(substr(md5((string) microtime(true)), 0, 4));
    }

    private function notifyOrderStatus(Order $order, string $title, string $message): void
    {
        $customerUserId = (int) optional($order->customer)->user_id;
        if ($customerUserId > 0) {
            $this->notificationService->notifyUser(
                $customerUserId,
                $title,
                $message.' (Order: '.$order->nomor_pesanan.')',
                'order',
                'order',
                $order->id
            );
        }

        $this->notificationService->notifyRoles(
            [User::ROLE_ADMIN, User::ROLE_STAFF_PENJUALAN, User::ROLE_STAFF_GUDANG, User::ROLE_KURIR],
            'Update Order '.$order->nomor_pesanan,
            $title.' - '.$message,
            'order',
            'order',
            $order->id
        );
    }

    private function normalizePaymentProofPath(string $path): string
    {
        $clean = trim(str_replace('\\', '/', $path));
        if ($clean === '') {
            return '';
        }
        if (str_starts_with($clean, 'storage/')) {
            $clean = substr($clean, 8);
        }
        if (str_starts_with($clean, 'public/')) {
            $clean = substr($clean, 7);
        }

        return ltrim($clean, '/');
    }
}
