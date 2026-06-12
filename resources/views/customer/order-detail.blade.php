@extends('customer.layout')

@section('title', 'Detail Pesanan')

@section('content')
<a href="{{ route('customer.orders') }}" class="btn btn-sm btn-outline-secondary mb-3">Kembali</a>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card p-3 mb-3">
    <div class="row g-2">
        <div class="col-md-4"><div class="text-muted">No Pesanan</div><div class="fw-semibold">{{ $order->nomor_pesanan }}</div></div>
        <div class="col-md-4"><div class="text-muted">Status</div><div><span class="badge text-bg-primary">{{ order_status_label($order->status_pesanan) }}</span></div></div>
        <div class="col-md-4"><div class="text-muted">Tanggal</div><div>{{ optional($order->tanggal_pesanan)->format('d/m/Y H:i') }}</div></div>
    </div>
</div>

<div class="card p-3 mb-3">
    <h6>Item Pesanan</h6>
    <div class="table-responsive">
        <table class="table table-sm align-middle mb-0">
            <thead><tr><th>Barang</th><th>Qty</th><th>Harga</th><th>Subtotal</th></tr></thead>
            <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ optional($item->dataBarang)->nama_barang ?? '-' }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>Rp{{ number_format($item->harga_jual_snapshot, 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="text-end mt-2 fw-bold">Total: Rp{{ number_format($order->total_nilai, 0, ',', '.') }}</div>
</div>

@php
    $paymentStatus = (string) ($order->payment_status ?? 'unpaid');
    $paymentLabel = match($paymentStatus) {
        'awaiting_verification' => 'Menunggu Verifikasi CS',
        'verified' => 'Terverifikasi',
        'rejected' => 'Ditolak, upload ulang',
        default => 'Belum Upload Bukti',
    };
    $paymentBadge = match($paymentStatus) {
        'awaiting_verification' => 'text-bg-warning',
        'verified' => 'text-bg-success',
        'rejected' => 'text-bg-danger',
        default => 'text-bg-secondary',
    };
@endphp
<div class="card p-3 mb-3">
    <h6>Pembayaran</h6>
    <div class="mb-2">
        <span class="badge {{ $paymentBadge }}">{{ $paymentLabel }}</span>
    </div>
    @if(filled($order->payment_proof_path))
        <div class="small mb-1">
            Bukti saat ini:
            <a href="{{ route('customer.orders.payment-proof.view', $order) }}" target="_blank" rel="noopener">Lihat Bukti</a>
        </div>
        @if(optional($order->payment_uploaded_at))
            <div class="small text-muted mb-1">Upload: {{ optional($order->payment_uploaded_at)->format('d/m/Y H:i') }}</div>
        @endif
        @if(filled($order->payment_note))
            <div class="small text-muted mb-1">Catatan customer: {{ $order->payment_note }}</div>
        @endif
    @endif
    @if(filled($order->payment_reject_note))
        <div class="small text-danger mb-2">Alasan ditolak: {{ $order->payment_reject_note }}</div>
    @endif
    @if(!in_array((string) $order->status_pesanan, ['completed', 'rejected'], true))
        <form method="POST" action="{{ route('customer.orders.payment-proof', $order) }}" enctype="multipart/form-data" class="row g-2">
            @csrf
            <div class="col-md-6">
                <input type="file" name="payment_proof" class="form-control form-control-sm" accept=".jpg,.jpeg,.png,.webp,.pdf" required>
            </div>
            <div class="col-md-4">
                <input type="text" name="payment_note" class="form-control form-control-sm" placeholder="Catatan pembayaran (opsional)">
            </div>
            <div class="col-md-2 d-grid">
                <button class="btn btn-primary btn-sm">Upload Bukti</button>
            </div>
        </form>
        <div class="small text-muted mt-1">Format: JPG/PNG/WEBP/PDF, max 4MB.</div>
    @endif
</div>

<div class="card p-3">
    <h6>Tracking Pengiriman</h6>
    @if($order->pengiriman)
        <div class="row g-2">
            <div class="col-md-4"><div class="text-muted small">Status</div><div class="fw-semibold">{{ strtoupper($order->pengiriman->status_pengiriman ?? '-') }}</div></div>
            <div class="col-md-4"><div class="text-muted small">Ekspedisi</div><div>{{ $order->pengiriman->nama_ekspedisi ?? '-' }}</div></div>
            <div class="col-md-4"><div class="text-muted small">No Resi</div><div>{{ $order->pengiriman->no_resi ?? '-' }}</div></div>
        </div>
        @if($order->status_pesanan === 'shipped')
            <form method="POST" action="{{ route('customer.orders.confirm-received', $order) }}" class="mt-3" onsubmit="return confirm('Konfirmasi bahwa barang sudah Anda terima?')">
                @csrf
                <button class="btn btn-success btn-sm">Konfirmasi Sudah Diterima</button>
                <div class="small text-muted mt-1">Klik tombol ini saat barang sudah benar-benar diterima.</div>
            </form>
        @elseif($order->status_pesanan === 'completed')
            <div class="small text-success mt-3">
                Pesanan sudah Anda konfirmasi diterima
                @if(optional($order->pengiriman)->tanggal_terima)
                    pada {{ optional($order->pengiriman->tanggal_terima)->format('d/m/Y H:i') }}.
                @else
                    .
                @endif
            </div>
        @endif
    @else
        <div class="text-muted">Data pengiriman belum tersedia.</div>
    @endif
</div>
@endsection
