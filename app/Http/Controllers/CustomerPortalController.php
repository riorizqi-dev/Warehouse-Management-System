<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\DataBarang;
use App\Models\InventoryLog;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Pengiriman;
use App\Models\StockReservation;
use App\Models\User;
use App\Models\UserNotification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class CustomerPortalController extends Controller
{
    public function __construct(private readonly NotificationService $notificationService) {}

    public function dashboard()
    {
        $customer = $this->resolveCustomer();

        $orders = Order::where('customer_id', $customer->id);
        $total = (clone $orders)->count();
        $active = (clone $orders)->whereIn('status_pesanan', ['pending', 'waiting_restock', 'reserved', 'picking', 'packed', 'shipped'])->count();
        $completed = (clone $orders)->where('status_pesanan', 'completed')->count();
        $recentOrders = (clone $orders)->with('pengiriman')->latest()->limit(6)->get();
        $notifications = collect();
        if (Schema::hasTable('notifications')) {
            UserNotification::where('user_id', auth()->id())
                ->where('is_read', false)
                ->update(['is_read' => true, 'read_at' => now()]);
            $notifications = UserNotification::where('user_id', auth()->id())->latest()->limit(8)->get();
        }

        return view('customer.dashboard', compact('total', 'active', 'completed', 'recentOrders', 'notifications'));
    }

    public function catalog(Request $request)
    {
        $query = DataBarang::query()->with('kategori');
        $keyword = trim((string) $request->query('q', ''));
        $kategoriId = (int) $request->query('kategori_id', 0);

        if ($keyword !== '') {
            $query->where(function ($q) use ($keyword) {
                $q->where('nama_barang', 'like', '%'.$keyword.'%')
                    ->orWhere('kode_barang', 'like', '%'.$keyword.'%')
                    ->orWhere('merek', 'like', '%'.$keyword.'%');
            });
        }
        if ($kategoriId > 0) {
            $query->where('kategori_id', $kategoriId);
        }

        $products = $query->orderBy('nama_barang')->paginate(16)->withQueryString();
        $products->getCollection()->transform(function ($product) {
            $product->stok_tersedia = $this->availableStock((int) $product->id);

            return $product;
        });
        $categories = \App\Models\KategoriBarang::orderBy('nama_kategori')->get();

        return view('customer.catalog', compact('products', 'categories', 'kategoriId'));
    }

    public function product(DataBarang $barang)
    {
        $stokTersedia = $this->availableStock((int) $barang->id);
        abort_if($stokTersedia <= 0, 404);
        $barang->load('kategori');

        return view('customer.product', compact('barang', 'stokTersedia'));
    }

    public function cart()
    {
        $cart = $this->getCartItems();
        $total = collect($cart)->sum('subtotal');

        return view('customer.cart', compact('cart', 'total'));
    }

    public function addToCart(Request $request, DataBarang $barang)
    {
        $validated = $request->validate([
            'qty' => 'required|integer|min:1|max:999',
        ]);

        $qty = (int) $validated['qty'];
        $available = $this->availableStock((int) $barang->id);
        if ($qty > $available) {
            return back()->withErrors(['qty' => 'Qty melebihi stok tersedia.']);
        }

        $cart = session('customer_cart', []);
        $key = (string) $barang->id;
        $existingQty = (int) ($cart[$key]['qty'] ?? 0);
        $newQty = $existingQty + $qty;

        if ($newQty > $available) {
            return back()->withErrors(['qty' => 'Qty di keranjang melebihi stok tersedia.']);
        }

        $price = (float) ($barang->harga_jual ?? $barang->harga ?? 0);
        $cart[$key] = [
            'data_barang_id' => $barang->id,
            'nama_barang' => $barang->nama_barang,
            'harga' => $price,
            'qty' => $newQty,
            'foto_url' => $barang->foto_url,
            'stok' => $available,
            'subtotal' => $newQty * $price,
        ];

        session(['customer_cart' => $cart]);

        return back()->with('success', 'Barang ditambahkan ke keranjang.');
    }

    public function updateCart(Request $request, DataBarang $barang)
    {
        $validated = $request->validate([
            'qty' => 'required|integer|min:1|max:999',
        ]);

        $qty = (int) $validated['qty'];
        $available = $this->availableStock((int) $barang->id);
        if ($qty > $available) {
            return back()->withErrors(['qty' => 'Qty melebihi stok tersedia.']);
        }

        $cart = session('customer_cart', []);
        $key = (string) $barang->id;
        if (! isset($cart[$key])) {
            return back();
        }

        $price = (float) ($barang->harga_jual ?? $barang->harga ?? 0);
        $cart[$key]['qty'] = $qty;
        $cart[$key]['stok'] = $available;
        $cart[$key]['subtotal'] = $qty * $price;
        session(['customer_cart' => $cart]);

        return back()->with('success', 'Keranjang diperbarui.');
    }

    public function removeFromCart(DataBarang $barang)
    {
        $cart = session('customer_cart', []);
        unset($cart[(string) $barang->id]);
        session(['customer_cart' => $cart]);

        return back()->with('success', 'Item dihapus dari keranjang.');
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'catatan' => 'nullable|string|max:1000',
        ]);

        $cart = session('customer_cart', []);
        if (empty($cart)) {
            return back()->withErrors(['cart' => 'Keranjang masih kosong.']);
        }

        $customer = $this->resolveCustomer();
        $userId = (int) auth()->id();
        $order = null;

        DB::transaction(function () use ($cart, $customer, $userId, $request, &$order) {
            $order = Order::create([
                'nomor_pesanan' => $this->generateOrderNumber(),
                'customer_id' => $customer->id,
                'tanggal_pesanan' => now(),
                'status_pesanan' => 'pending',
                'order_source' => 'customer_portal',
                'catatan' => $request->input('catatan'),
                'created_by' => $userId,
            ]);

            $totalQty = 0;
            $totalNilai = 0;
            foreach ($cart as $row) {
                $barang = DataBarang::lockForUpdate()->findOrFail((int) $row['data_barang_id']);
                $qty = (int) $row['qty'];
                $price = (float) ($barang->harga_jual ?? $barang->harga ?? 0);
                $subtotal = $qty * $price;

                $item = OrderItem::create([
                    'order_id' => $order->id,
                    'data_barang_id' => $barang->id,
                    'qty' => $qty,
                    'harga_jual_snapshot' => $price,
                    'subtotal' => $subtotal,
                ]);

                $totalQty += $qty;
                $totalNilai += $subtotal;

                $available = $this->availableStock($barang->id, $order->id);
                if ($available >= $qty) {
                    StockReservation::create([
                        'order_id' => $order->id,
                        'order_item_id' => $item->id,
                        'data_barang_id' => $barang->id,
                        'qty_reserved' => $qty,
                        'status' => 'active',
                        'reserved_by' => $userId,
                        'reserved_at' => now(),
                        'notes' => 'Reservasi dari customer portal',
                    ]);
                } else {
                    $order->status_pesanan = 'waiting_restock';
                }
            }

            if ($order->status_pesanan !== 'waiting_restock') {
                $order->status_pesanan = 'reserved';
            }

            $order->total_item = count($cart);
            $order->total_qty = $totalQty;
            $order->total_nilai = $totalNilai;
            $order->save();

            $this->notificationService->notifyUser(
                $userId,
                'Pesanan Berhasil Dibuat',
                'Pesanan '.$order->nomor_pesanan.' telah diterima sistem.',
                'order',
                'order',
                $order->id
            );

            $catatan = trim((string) ($order->catatan ?? ''));
            $catatanInfo = $catatan !== '' ? ' | Catatan: '.$catatan : '';
            $this->notificationService->notifyRoles(
                [User::ROLE_SUPERADMIN, User::ROLE_ADMIN, User::ROLE_STAFF_PENJUALAN],
                'Pesanan Baru '.$order->nomor_pesanan,
                'Pesanan baru dari customer portal menunggu tindak lanjut.'.$catatanInfo,
                'order',
                'order',
                $order->id
            );
        });

        session()->forget('customer_cart');

        return redirect()->route('customer.orders.show', $order)->with('success', 'Pesanan berhasil dibuat.');
    }

    public function orders()
    {
        $customer = $this->resolveCustomer();
        $orders = Order::with(['items.dataBarang', 'pengiriman'])
            ->where('customer_id', $customer->id)
            ->latest()
            ->paginate(12);

        return view('customer.orders', compact('orders'));
    }

    public function orderDetail(Order $order)
    {
        $customer = $this->resolveCustomer();
        abort_if((int) $order->customer_id !== (int) $customer->id, 403);
        $order->load(['items.dataBarang', 'pengiriman']);

        return view('customer.order-detail', compact('order'));
    }

    public function confirmReceived(Order $order)
    {
        $customer = $this->resolveCustomer();
        abort_if((int) $order->customer_id !== (int) $customer->id, 403);

        if ($order->status_pesanan === Order::STATUS_COMPLETED) {
            return back()->with('success', 'Pesanan sudah berstatus diterima.');
        }

        if ($order->status_pesanan !== Order::STATUS_SHIPPED) {
            return back()->withErrors(['status' => 'Pesanan belum bisa dikonfirmasi diterima.']);
        }

        $confirmed = false;
        DB::transaction(function () use ($order, &$confirmed) {
            $lockedOrder = Order::with(['customer', 'pengiriman'])->lockForUpdate()->findOrFail($order->id);
            if ($lockedOrder->status_pesanan !== Order::STATUS_SHIPPED) {
                return;
            }

            if ($lockedOrder->pengiriman) {
                $lockedOrder->pengiriman->update([
                    'status_pengiriman' => 'terkirim',
                    'tanggal_terima' => now(),
                ]);
            } else {
                Pengiriman::create([
                    'order_id' => $lockedOrder->id,
                    'barang_keluar_id' => optional($lockedOrder->barangKeluar)->id,
                    'status_pengiriman' => 'terkirim',
                    'tanggal_terima' => now(),
                ]);
            }

            $lockedOrder->update(['status_pesanan' => Order::STATUS_COMPLETED]);

            InventoryLog::create([
                'user_id' => auth()->id(),
                'data_barang_id' => null,
                'action' => 'order_complete_customer',
                'changes' => [
                    'order_id' => $lockedOrder->id,
                    'nomor_pesanan' => $lockedOrder->nomor_pesanan,
                    'confirmed_at' => now()->toDateTimeString(),
                ],
            ]);

            $this->notificationService->notifyRoles(
                [User::ROLE_SUPERADMIN, User::ROLE_ADMIN, User::ROLE_STAFF_PENJUALAN, User::ROLE_STAFF_GUDANG, User::ROLE_KURIR],
                'Pesanan Diterima Customer',
                'Customer telah konfirmasi penerimaan order '.$lockedOrder->nomor_pesanan.'.',
                'order',
                'order',
                $lockedOrder->id
            );

            $customerUserId = (int) optional($lockedOrder->customer)->user_id;
            if ($customerUserId > 0) {
                $this->notificationService->notifyUser(
                    $customerUserId,
                    'Konfirmasi Berhasil',
                    'Terima kasih. Pesanan '.$lockedOrder->nomor_pesanan.' telah dikonfirmasi diterima.',
                    'order',
                    'order',
                    $lockedOrder->id
                );
            }

            $confirmed = true;
        });

        if (! $confirmed) {
            return back()->withErrors(['status' => 'Status pesanan berubah. Silakan refresh halaman.']);
        }

        return back()->with('success', 'Pesanan berhasil dikonfirmasi diterima.');
    }

    public function uploadPaymentProof(Request $request, Order $order)
    {
        $customer = $this->resolveCustomer();
        abort_if((int) $order->customer_id !== (int) $customer->id, 403);

        if (in_array((string) $order->status_pesanan, [Order::STATUS_REJECTED, Order::STATUS_COMPLETED], true)) {
            return back()->withErrors(['payment' => 'Bukti bayar tidak dapat diupload untuk status pesanan ini.']);
        }

        $validated = $request->validate([
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,webp,pdf|max:4096',
            'payment_note' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($order, $validated) {
            $lockedOrder = Order::lockForUpdate()->findOrFail($order->id);

            $oldPath = (string) ($lockedOrder->payment_proof_path ?? '');
            $newPath = $validated['payment_proof']->store('payment-proofs', 'public');

            $lockedOrder->update([
                'payment_status' => Order::PAYMENT_AWAITING_VERIFICATION,
                'payment_proof_path' => $newPath,
                'payment_note' => trim((string) ($validated['payment_note'] ?? '')) ?: null,
                'payment_uploaded_at' => now(),
                'payment_verified_by' => null,
                'payment_verified_at' => null,
                'payment_reject_note' => null,
            ]);

            if ($oldPath !== '' && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }

            InventoryLog::create([
                'user_id' => auth()->id(),
                'data_barang_id' => null,
                'action' => 'order_payment_upload',
                'changes' => [
                    'order_id' => $lockedOrder->id,
                    'nomor_pesanan' => $lockedOrder->nomor_pesanan,
                ],
            ]);

            $this->notificationService->notifyRoles(
                [User::ROLE_SUPERADMIN, User::ROLE_ADMIN, User::ROLE_STAFF_PENJUALAN],
                'Bukti Bayar Masuk',
                'Customer upload bukti bayar untuk order '.$lockedOrder->nomor_pesanan.'.',
                'order',
                'order',
                $lockedOrder->id
            );
        });

        return back()->with('success', 'Bukti pembayaran berhasil diupload, menunggu verifikasi.');
    }

    public function viewPaymentProof(Order $order)
    {
        $customer = $this->resolveCustomer();
        abort_if((int) $order->customer_id !== (int) $customer->id, 403);

        $proofPath = $this->normalizePaymentProofPath((string) ($order->payment_proof_path ?? ''));
        if ($proofPath === '' || ! Storage::disk('public')->exists($proofPath)) {
            abort(404, 'Bukti pembayaran tidak ditemukan.');
        }

        return response()->file(Storage::disk('public')->path($proofPath));
    }

    private function resolveCustomer(): Customer
    {
        $user = auth()->user();
        $customer = $user->customer;
        if ($customer) {
            return $customer;
        }

        return Customer::create([
            'user_id' => $user->id,
            'kode_customer' => 'CUS-'.now()->format('Ymd').'-'.str_pad((string) (Customer::count() + 1), 4, '0', STR_PAD_LEFT),
            'nama_customer' => $user->name,
            'email' => $user->email,
            'status' => 'aktif',
        ]);
    }

    private function getCartItems(): array
    {
        $cart = session('customer_cart', []);
        foreach ($cart as $key => $row) {
            $barang = DataBarang::find((int) $row['data_barang_id']);
            if (! $barang || (int) $barang->stok <= 0) {
                unset($cart[$key]);

                continue;
            }
            $price = (float) ($barang->harga_jual ?? $barang->harga ?? 0);
            $available = $this->availableStock((int) $barang->id);
            if ($available <= 0) {
                unset($cart[$key]);

                continue;
            }
            $qty = min((int) $row['qty'], $available);
            $cart[$key]['qty'] = max(1, $qty);
            $cart[$key]['harga'] = $price;
            $cart[$key]['stok'] = $available;
            $cart[$key]['subtotal'] = $cart[$key]['qty'] * $price;
            $cart[$key]['foto_url'] = $barang->foto_url;
        }
        session(['customer_cart' => $cart]);

        return array_values($cart);
    }

    private function availableStock(int $dataBarangId, ?int $excludeOrderId = null): int
    {
        $physical = (int) DataBarang::whereKey($dataBarangId)->value('stok');
        $reservedQuery = StockReservation::where('data_barang_id', $dataBarangId)->where('status', 'active');
        if ($excludeOrderId !== null) {
            $reservedQuery->where('order_id', '!=', $excludeOrderId);
        }
        $reserved = (int) $reservedQuery->sum('qty_reserved');

        return max(0, $physical - $reserved);
    }

    private function generateOrderNumber(): string
    {
        return 'ORD-'.now()->format('Ymd-His').'-'.strtoupper(substr(md5((string) microtime(true)), 0, 4));
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
