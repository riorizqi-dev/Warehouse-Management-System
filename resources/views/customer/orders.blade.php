@extends('customer.layout')

@section('title', 'Riwayat Pesanan')

@section('content')
<h4 class="mb-3">Riwayat Pesanan</h4>

<div class="card p-3">
    <div class="table-responsive">
        <table class="table table-sm align-middle mb-0">
            <thead><tr><th>No Pesanan</th><th>Tanggal</th><th>Total</th><th>Status</th><th></th></tr></thead>
            <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>{{ $order->nomor_pesanan }}</td>
                    <td>{{ optional($order->tanggal_pesanan)->format('d/m/Y H:i') }}</td>
                    <td>Rp{{ number_format($order->total_nilai, 0, ',', '.') }}</td>
                    <td><span class="badge text-bg-primary">{{ order_status_label($order->status_pesanan) }}</span></td>
                    <td><a href="{{ route('customer.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">Detail</a></td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-muted">Belum ada pesanan.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-2">{{ $orders->links() }}</div>
</div>
@endsection
