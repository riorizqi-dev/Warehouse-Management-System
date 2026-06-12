@extends('customer.layout')

@section('title', 'Keranjang')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Keranjang</h4>
    <a href="{{ route('customer.catalog') }}" class="btn btn-sm btn-outline-secondary">Tambah Barang</a>
</div>

<div class="card p-3 mb-3">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Barang</th><th>Harga</th><th>Qty</th><th>Subtotal</th><th></th></tr></thead>
            <tbody>
            @forelse($cart as $row)
                <tr>
                    <td>{{ $row['nama_barang'] }}</td>
                    <td>Rp{{ number_format($row['harga'], 0, ',', '.') }}</td>
                    <td>
                        <form method="POST" action="{{ route('customer.cart.update', $row['data_barang_id']) }}" class="d-flex gap-2">
                            @csrf @method('PUT')
                            <input type="number" name="qty" min="1" max="{{ $row['stok'] }}" value="{{ $row['qty'] }}" class="form-control form-control-sm" style="width:90px;">
                            <button class="btn btn-sm btn-outline-primary">Update</button>
                        </form>
                    </td>
                    <td>Rp{{ number_format($row['subtotal'], 0, ',', '.') }}</td>
                    <td>
                        <form method="POST" action="{{ route('customer.cart.remove', $row['data_barang_id']) }}">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-muted">Keranjang kosong.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card p-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="mb-0">Total: Rp{{ number_format($total, 0, ',', '.') }}</h5>
    </div>
    <form method="POST" action="{{ route('customer.checkout') }}">
        @csrf
        <label class="form-label">Catatan Pesanan (opsional)</label>
        <textarea name="catatan" class="form-control mb-2" rows="3" placeholder="Contoh: kirim sore hari"></textarea>
        <button class="btn btn-primary" {{ empty($cart) ? 'disabled' : '' }}>Submit Pesanan</button>
    </form>
</div>
@endsection
