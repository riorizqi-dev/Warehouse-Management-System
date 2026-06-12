@extends('customer.layout')

@section('title', 'Katalog Barang')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Katalog Barang</h4>
    <a href="{{ route('customer.cart') }}" class="btn btn-primary btn-sm">Lihat Keranjang</a>
</div>

<form method="GET" class="card p-3 mb-3">
    <div class="row g-2">
        <div class="col-md-6"><input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari barang..."></div>
        <div class="col-md-4">
            <select name="kategori_id" class="form-select">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected($kategoriId === $category->id)>{{ $category->nama_kategori }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2"><button class="btn btn-outline-secondary w-100">Filter</button></div>
    </div>
</form>

<div class="row g-3">
    @forelse($products as $item)
        <div class="col-6 col-lg-3">
            <div class="card h-100">
                <img src="{{ $item->foto_url }}" alt="{{ $item->nama_barang }}" style="width:100%;height:140px;object-fit:cover;border-top-left-radius:14px;border-top-right-radius:14px;">
                <div class="p-2">
                    <div class="small text-muted">{{ optional($item->kategori)->nama_kategori ?? '-' }}</div>
                    <div class="fw-semibold" style="min-height:40px;">{{ $item->nama_barang }}</div>
                    <div class="text-primary fw-bold">Rp{{ number_format($item->harga_jual ?? $item->harga, 0, ',', '.') }}</div>
                    <div class="small text-muted mb-2">Stok tersedia: {{ (int) ($item->stok_tersedia ?? 0) }}</div>
                    @if((int) ($item->stok_tersedia ?? 0) <= 0)
                        <span class="badge text-bg-danger mb-2">Stok Habis</span>
                    @endif
                    @if((int) ($item->stok_tersedia ?? 0) > 0)
                        <a href="{{ route('customer.catalog.show', $item) }}" class="btn btn-sm btn-outline-primary w-100">Detail</a>
                    @else
                        <button class="btn btn-sm btn-outline-secondary w-100" disabled>Tidak Tersedia</button>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-12"><div class="alert alert-light">Barang tidak ditemukan.</div></div>
    @endforelse
</div>
<div class="mt-3">{{ $products->links() }}</div>
@endsection
