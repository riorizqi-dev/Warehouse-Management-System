@extends('customer.layout')

@section('title', 'Detail Barang')

@section('content')
<a href="{{ route('customer.catalog') }}" class="btn btn-sm btn-outline-secondary mb-3">Kembali ke Katalog</a>

<div class="card p-3">
    <div class="row g-3">
        <div class="col-md-5">
            <img src="{{ $barang->foto_url }}" alt="{{ $barang->nama_barang }}" style="width:100%;height:320px;object-fit:cover;border-radius:12px;">
        </div>
        <div class="col-md-7">
            <h4>{{ $barang->nama_barang }}</h4>
            <div class="text-muted mb-2">{{ optional($barang->kategori)->nama_kategori ?? '-' }} | {{ $barang->merek ?? '-' }}</div>
            <h5 class="text-primary">Rp{{ number_format($barang->harga_jual ?? $barang->harga, 0, ',', '.') }}</h5>
            <p class="mb-2">Stok tersedia: <strong>{{ $stokTersedia }}</strong></p>
            <p class="text-muted">Kode: {{ $barang->kode_barang ?? '-' }} | Rak: {{ $barang->lokasi_rak ?? '-' }}</p>

            <form method="POST" action="{{ route('customer.cart.add', $barang) }}" class="d-flex gap-2 align-items-center">
                @csrf
                <input type="number" name="qty" min="1" max="{{ $stokTersedia }}" value="1" class="form-control" style="max-width:110px;" required>
                <button class="btn btn-primary">Tambah ke Keranjang</button>
            </form>
        </div>
    </div>
</div>
@endsection
