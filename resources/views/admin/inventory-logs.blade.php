@extends('admin.master-admin')

@section('title', $pageTitle ?? 'Riwayat Perubahan Data Barang')

@section('content')
<div class="page-heading">
    <h3>{{ $pageTitle ?? 'Riwayat Perubahan Data Barang' }}</h3>
    <p class="text-muted">{{ $pageDescription ?? 'Riwayat perubahan data barang' }}</p>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <div class="mb-3 d-flex gap-2 flex-wrap log-filters">
            <a href="{{ route('inventory.logs', ['type' => 'masuk']) }}" class="btn btn-sm {{ ($logType ?? 'all') === 'masuk' ? 'btn-primary' : 'btn-outline-light' }}">Barang Masuk</a>
            <a href="{{ route('inventory.logs', ['type' => 'keluar']) }}" class="btn btn-sm {{ ($logType ?? 'all') === 'keluar' ? 'btn-primary' : 'btn-outline-light' }}">Barang Keluar</a>
            <a href="{{ route('inventory.logs') }}" class="btn btn-sm {{ ($logType ?? 'all') === 'all' ? 'btn-primary' : 'btn-outline-light' }}">Semua Log</a>
        </div>

        <table class="table table-sm">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Pengguna</th>
                    <th>Aksi</th>
                    <th>Barang</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                    @php
                        $actionText = match($log->action) {
                            'create' => 'Barang Baru',
                            'delete' => 'Barang Dihapus',
                            'stock_in' => 'Stok Masuk',
                            'stock_out' => 'Stok Keluar',
                            'order_reserved' => 'Stok Reserved',
                            'order_reservation_released' => 'Release Reservasi',
                            'order_complete_customer' => 'Order Diterima Customer',
                            'order_payment_upload' => 'Upload Bukti Bayar',
                            'order_payment_verified' => 'Pembayaran Diverifikasi',
                            'order_payment_rejected' => 'Pembayaran Ditolak',
                            'update' => 'Update Data',
                            default => ucfirst((string) $log->action),
                        };
                        $badgeClass = match($log->action) {
                            'create', 'stock_in', 'order_reserved', 'order_complete_customer', 'order_payment_verified' => 'bg-success',
                            'order_payment_upload' => 'bg-warning text-dark',
                            'delete', 'stock_out', 'order_reservation_released' => 'bg-danger',
                            'order_payment_rejected' => 'bg-danger',
                            'update' => 'bg-info text-dark',
                            default => 'bg-secondary',
                        };
                    @endphp
                    <tr>
                        <td>{{ optional($log->created_at)->format('d/m/Y H:i') }}</td>
                        <td>{{ optional($log->user)->name ?? '-' }}</td>
                        <td><span class="badge {{ $badgeClass }}">{{ $actionText }}</span></td>
                        <td>{{ optional($log->dataBarang)->nama_barang ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-2">{{ $logs->links() }}</div>
    </div>
    </div>

<style>
    .log-filters .btn {
        border-radius: 999px;
        padding-inline: 0.9rem;
    }
</style>
@endsection



