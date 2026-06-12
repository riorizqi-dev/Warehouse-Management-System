<?php

namespace App\Http\Controllers;

use App\Models\KategoriBarang;
use Illuminate\Http\Request;

class KategoriBarangController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $keyword = trim((string) $request->query('q', ''));
        $kategori = KategoriBarang::query()
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where('nama_kategori', 'like', '%'.$keyword.'%')
                    ->orWhere('deskripsi', 'like', '%'.$keyword.'%');
            })
            ->orderBy('nama_kategori')
            ->paginate(10)
            ->withQueryString();

        $selectedCategory = null;
        $barang = null;
        if ($request->filled('kategori_id')) {
            $selectedCategory = (int) $request->query('kategori_id');
            $barang = \App\Models\DataBarang::with('kategori', 'user')
                ->where('kategori_id', $selectedCategory)
                ->when($keyword !== '', function ($query) use ($keyword) {
                    $query->where('nama_barang', 'like', '%'.$keyword.'%');
                })
                ->latest()
                ->paginate(12)
                ->withQueryString();
        }

        return view('kategori-barang', compact('kategori', 'barang', 'selectedCategory'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->check() && auth()->user()->canManageWarehouse(), 403);

        $data = $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);
        KategoriBarang::create($data);

        return back()->with('success', 'Kategori ditambahkan');
    }

    public function update(Request $request, $id)
    {
        abort_unless(auth()->check() && auth()->user()->canManageWarehouse(), 403);

        $data = $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);
        $row = KategoriBarang::findOrFail($id);
        $row->update($data);

        return back()->with('success', 'Kategori diperbarui');
    }

    public function destroy($id)
    {
        abort_unless(auth()->check() && auth()->user()->canManageWarehouse(), 403);

        $row = KategoriBarang::findOrFail($id);
        $row->delete();

        return back()->with('success', 'Kategori dihapus');
    }
}
