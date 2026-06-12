@extends('customer.layout')

@section('title', 'Dashboard Customer')

@section('content')
<h4 class="mb-3">Dashboard Customer</h4>

<div class="row g-3 mb-3">
    <div class="col-12 col-md-4"><div class="card p-3"><div class="text-muted">Total Pesanan</div><h3 class="mb-0">{{ $total }}</h3></div></div>
    <div class="col-12 col-md-4"><div class="card p-3"><div class="text-muted">Pesanan Aktif</div><h3 class="mb-0">{{ $active }}</h3></div></div>
    <div class="col-12 col-md-4"><div class="card p-3"><div class="text-muted">Pesanan Selesai</div><h3 class="mb-0">{{ $completed }}</h3></div></div>
</div>

<div class="card p-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="mb-0">Ringkasan Pesanan Terbaru</h6>
        <a href="{{ route('customer.orders') }}" class="btn btn-sm btn-outline-secondary">Lihat Semua</a>
    </div>
    <div class="table-responsive">
        <table class="table table-sm align-middle mb-0">
            <thead><tr><th>No Pesanan</th><th>Status</th><th>Tanggal</th><th></th></tr></thead>
            <tbody>
            @forelse($recentOrders as $order)
                <tr>
                    <td>{{ $order->nomor_pesanan }}</td>
                    <td><span class="badge text-bg-primary">{{ order_status_label($order->status_pesanan) }}</span></td>
                    <td>{{ optional($order->tanggal_pesanan)->format('d/m/Y H:i') }}</td>
                    <td><a href="{{ route('customer.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">Detail</a></td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-muted">Belum ada pesanan.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card p-3 mt-3">
    <h6 class="mb-2">Notifikasi Sistem</h6>
    <div class="table-responsive">
        <table class="table table-sm align-middle mb-0">
            <thead><tr><th>Judul</th><th>Pesan</th><th>Waktu</th></tr></thead>
            <tbody>
            @forelse($notifications as $notification)
                <tr>
                    <td>
                        <span class="fw-semibold">{{ $notification->title }}</span>
                        @if(!$notification->is_read)
                            <span class="badge text-bg-warning ms-1">Baru</span>
                        @endif
                    </td>
                    <td>{{ $notification->message }}</td>
                    <td>{{ optional($notification->created_at)->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr><td colspan="3" class="text-muted">Belum ada notifikasi.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
