<?php

namespace App\Http\Controllers;

use App\Models\DataBarang;
use App\Models\InventoryLog;
use App\Models\KategoriBarang;
use App\Models\StockReservation;
use App\Services\StockMovementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DataBarangController extends Controller
{
    public function __construct(private readonly StockMovementService $stockMovementService) {}

    public function index(Request $request)
    {
        $keyword = trim((string) $request->query('q', ''));
        $menu = strtolower((string) $request->query('menu', 'barang'));
        $isStokView = $menu === 'stok';

        $query = DataBarang::with(['kategori', 'user']);
        if ($keyword !== '') {
            $query->where(function ($q) use ($keyword) {
                $q->where('nama_barang', 'like', '%'.$keyword.'%')
                    ->orWhere('kode_barang', 'like', '%'.$keyword.'%')
                    ->orWhere('merek', 'like', '%'.$keyword.'%')
                    ->orWhereHas('kategori', function ($kq) use ($keyword) {
                        $kq->where('nama_kategori', 'like', '%'.$keyword.'%');
                    });
            });
        }
        if ($isStokView) {
            $query->orderBy('stok')->orderBy('nama_barang');
        } else {
            $query->latest();
        }

        $barang = $query->paginate(12)->withQueryString();
        $kategoriList = KategoriBarang::orderBy('nama_kategori')->get();

        $stockSummary = [
            'total_item' => DataBarang::count(),
            'total_stok' => (int) DataBarang::sum('stok'),
            'stok_reserved' => (int) StockReservation::where('status', 'active')->sum('qty_reserved'),
            'stok_tersedia' => max(0, (int) DataBarang::sum('stok') - (int) StockReservation::where('status', 'active')->sum('qty_reserved')),
            'stok_habis' => DataBarang::where('stok', 0)->count(),
            'stok_menipis' => DataBarang::where('stok', '>', 0)->whereColumn('stok', '<=', 'stok_minimum')->count(),
        ];

        $editId = $isStokView ? null : $request->query('edit');
        $editRow = null;
        if ($editId) {
            $editRow = DataBarang::with('kategori')->find($editId);
        }

        return view('data-barang', compact('barang', 'editRow', 'kategoriList', 'isStokView', 'stockSummary'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->canManageWarehouse(), 403, 'Anda tidak memiliki izin untuk menambah data barang.');

        $data = $request->validate([
            'kode_barang' => 'nullable|string|max:255|unique:data_barangs,kode_barang',
            'nama_barang' => 'required|string|max:255',
            'merek' => 'nullable|string|max:255',
            'tipe_model' => 'nullable|string|max:255',
            'garansi_bulan' => 'nullable|integer|min:0',
            'lokasi_rak' => 'nullable|string|max:255',
            'stok' => 'required|integer|min:0',
            'stok_minimum' => 'nullable|integer|min:0',
            'harga' => 'required|numeric|min:0',
            'harga_beli' => 'nullable|numeric|min:0',
            'harga_jual' => 'nullable|numeric|min:0',
            'satuan' => 'nullable|string|max:20',
            'foto_barang' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,jfif|max:2048',
            'kategori_id' => 'nullable|exists:kategori_barangs,id',
        ]);

        if ($request->hasFile('foto_barang')) {
            $filename = time().'_'.preg_replace('/[^a-zA-Z0-9._-]/', '_', $request->file('foto_barang')->getClientOriginalName());
            $path = $request->file('foto_barang')->storeAs('foto_barang', $filename, 'public');
            $data['foto_barang'] = $path;
        }

        $data['user_id'] = Auth::id();
        $data['garansi_bulan'] = (int) ($data['garansi_bulan'] ?? 0);
        $data['stok_minimum'] = (int) ($data['stok_minimum'] ?? 5);
        $data['harga_beli'] = (float) ($data['harga_beli'] ?? 0);
        $data['harga_jual'] = (float) ($data['harga_jual'] ?? $data['harga']);
        $data['satuan'] = $data['satuan'] ?? 'unit';
        $barang = DataBarang::create($data);

        InventoryLog::create([
            'user_id' => Auth::id(),
            'data_barang_id' => $barang->id,
            'action' => 'create',
            'changes' => $barang->toArray(),
        ]);

        if ((int) $barang->stok > 0) {
            $this->stockMovementService->record([
                'data_barang_id' => $barang->id,
                'movement_type' => 'initial_stock',
                'qty_change' => (int) $barang->stok,
                'qty_before' => 0,
                'qty_after' => (int) $barang->stok,
                'reference_type' => 'data_barang',
                'reference_id' => $barang->id,
                'notes' => 'Inisialisasi stok saat barang dibuat',
                'created_by' => Auth::id(),
            ]);
        }

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        abort_unless(auth()->user()->canManageWarehouse(), 403, 'Anda tidak memiliki izin untuk mengubah data barang.');

        $data = $request->validate([
            'kode_barang' => 'nullable|string|max:255|unique:data_barangs,kode_barang,'.$id,
            'nama_barang' => 'required|string|max:255',
            'merek' => 'nullable|string|max:255',
            'tipe_model' => 'nullable|string|max:255',
            'garansi_bulan' => 'nullable|integer|min:0',
            'lokasi_rak' => 'nullable|string|max:255',
            'stok' => 'required|integer|min:0',
            'stok_minimum' => 'nullable|integer|min:0',
            'harga' => 'required|numeric|min:0',
            'harga_beli' => 'nullable|numeric|min:0',
            'harga_jual' => 'nullable|numeric|min:0',
            'satuan' => 'nullable|string|max:20',
            'foto_barang' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,jfif|max:2048',
            'kategori_id' => 'nullable|exists:kategori_barangs,id',
        ]);

        $row = DataBarang::findOrFail($id);
        $this->authorize('update', $row);

        if ($request->hasFile('foto_barang')) {
            if ($row->foto_barang && Storage::disk('public')->exists($row->foto_barang)) {
                Storage::disk('public')->delete($row->foto_barang);
            }

            $filename = time().'_'.preg_replace('/[^a-zA-Z0-9._-]/', '_', $request->file('foto_barang')->getClientOriginalName());
            $path = $request->file('foto_barang')->storeAs('foto_barang', $filename, 'public');
            $data['foto_barang'] = $path;
        }

        $original = $row->getOriginal();
        $beforeStok = (int) ($original['stok'] ?? 0);
        $data['garansi_bulan'] = (int) ($data['garansi_bulan'] ?? 0);
        $data['stok_minimum'] = (int) ($data['stok_minimum'] ?? 5);
        $data['harga_beli'] = (float) ($data['harga_beli'] ?? 0);
        $data['harga_jual'] = (float) ($data['harga_jual'] ?? $data['harga']);
        $data['satuan'] = $data['satuan'] ?? 'unit';
        $row->update($data);
        $afterStok = (int) $row->stok;

        $action = 'update';
        if ($afterStok > $beforeStok) {
            $action = 'stock_in';
        } elseif ($afterStok < $beforeStok) {
            $action = 'stock_out';
        }

        InventoryLog::create([
            'user_id' => Auth::id(),
            'data_barang_id' => $row->id,
            'action' => $action,
            'changes' => [
                'before' => $original,
                'after' => $row->toArray(),
            ],
        ]);

        if ($afterStok !== $beforeStok) {
            $this->stockMovementService->record([
                'data_barang_id' => $row->id,
                'movement_type' => $afterStok > $beforeStok ? 'stock_adjustment_in' : 'stock_adjustment_out',
                'qty_change' => $afterStok - $beforeStok,
                'qty_before' => $beforeStok,
                'qty_after' => $afterStok,
                'reference_type' => 'data_barang',
                'reference_id' => $row->id,
                'notes' => 'Penyesuaian stok dari edit data barang',
                'created_by' => Auth::id(),
            ]);
        }

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil diubah.');
    }

    public function destroy($id)
    {
        abort_unless(auth()->user()->canManageWarehouse(), 403, 'Anda tidak memiliki izin untuk menghapus data barang.');

        $row = DataBarang::findOrFail($id);
        $this->authorize('delete', $row);

        $snapshot = $row->toArray();

        InventoryLog::create([
            'user_id' => Auth::id(),
            'data_barang_id' => $row->id, // foreign key masih ada
            'action' => 'delete',
            'changes' => $snapshot,
        ]);

        if ($row->foto_barang && Storage::disk('public')->exists($row->foto_barang)) {
            Storage::disk('public')->delete($row->foto_barang);
        }

        $row->delete();

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil dihapus.');
    }

    public function edit($id)
    {
        return redirect()->route('barang.index', ['edit' => $id]);
    }

    public function stockOut(Request $request, $id)
    {
        abort_unless(auth()->user()->canManageWarehouse(), 403, 'Anda tidak memiliki izin untuk transaksi barang keluar.');

        $validated = $request->validate([
            'qty_keluar' => 'required|integer|min:1',
            'catatan_keluar' => 'nullable|string|max:500',
        ]);

        $qtyKeluar = (int) $validated['qty_keluar'];
        $catatan = $validated['catatan_keluar'] ?? null;

        try {
            DB::transaction(function () use ($id, $qtyKeluar, $catatan) {
                $row = DataBarang::lockForUpdate()->findOrFail($id);

                if ((int) $row->stok < $qtyKeluar) {
                    abort(422, 'Jumlah barang keluar melebihi stok yang tersedia.');
                }

                $stokSebelum = (int) $row->stok;
                $stokSesudah = $stokSebelum - $qtyKeluar;

                $row->stok = $stokSesudah;
                $row->save();

                InventoryLog::create([
                    'user_id' => Auth::id(),
                    'data_barang_id' => $row->id,
                    'action' => 'stock_out',
                    'changes' => [
                        'qty_keluar' => $qtyKeluar,
                        'stok_sebelum' => $stokSebelum,
                        'stok_sesudah' => $stokSesudah,
                        'catatan' => $catatan,
                    ],
                ]);

                $this->stockMovementService->record([
                    'data_barang_id' => $row->id,
                    'movement_type' => 'stock_out_manual',
                    'qty_change' => -$qtyKeluar,
                    'qty_before' => $stokSebelum,
                    'qty_after' => $stokSesudah,
                    'reference_type' => 'stock_out',
                    'reference_id' => $row->id,
                    'notes' => $catatan ?: 'Transaksi barang keluar manual',
                    'created_by' => Auth::id(),
                ]);
            });
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            throw $e;
        } catch (\Throwable $e) {
            return back()->withErrors(['qty_keluar' => 'Transaksi barang keluar gagal diproses.'])->withInput();
        }

        return redirect()->route('barang.index')
            ->with('success', 'Transaksi barang keluar berhasil. Stok telah diperbarui.');
    }
}
