@extends('admin.master-admin')

@section('title', 'Pesanan Customer')

@push('styles')
<style>
    .orders-table-wrap {
        overflow-x: auto;
        padding-bottom: 4px;
        scrollbar-width: thin;
        scrollbar-color: rgba(148, 163, 184, 0.55) rgba(17, 24, 39, 0.65);
    }

    .orders-table-wrap::-webkit-scrollbar {
        height: 9px;
    }

    .orders-table-wrap::-webkit-scrollbar-track {
        background: rgba(17, 24, 39, 0.65);
        border-radius: 999px;
    }

    .orders-table-wrap::-webkit-scrollbar-thumb {
        background: linear-gradient(90deg, rgba(148, 163, 184, 0.78), rgba(100, 116, 139, 0.78));
        border-radius: 999px;
        border: 2px solid rgba(17, 24, 39, 0.65);
    }

    .orders-table-wrap table {
        min-width: 1050px;
    }

    .order-actions {
        min-width: 240px;
    }

    .order-actions .badge {
        white-space: normal;
    }
</style>
@endpush

@section('content')
<div class="page-heading mb-3">
    <h3>Pesanan Customer</h3>
    <p class="text-muted mb-0">Alur operasional: pending -> reserved -> picking -> packed -> shipped -> completed.</p>
</div>

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

@php
    $authUser = auth()->user();
    $canSales = $authUser->canManageSales();
    $canWarehouse = $authUser->canManageWarehouse();
    $canShipping = $authUser->canManageShipping();
@endphp

@if($canSales)
<div class="card mb-3">
    <div class="card-header"><h5 class="mb-0">Input Pesanan Baru</h5></div>
    <div class="card-body">
        <form method="POST" action="{{ route('orders.store') }}" id="orderForm">
            @csrf
            <div class="row g-2 mb-2">
                <div class="col-md-4">
                    <label class="form-label">Customer</label>
                    <select name="customer_id" class="form-select" required>
                        <option value="">Pilih customer</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->nama_customer }} ({{ $customer->kode_customer }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Catatan</label>
                    <input type="text" name="catatan" class="form-control" placeholder="Opsional">
                </div>
            </div>

            <div id="itemsWrap" class="d-grid gap-2">
                <div class="row g-2 item-row">
                    <div class="col-md-6">
                        <select name="items[0][data_barang_id]" class="form-select" required>
                            <option value="">Pilih barang</option>
                            @foreach($barangs as $barang)
                                <option value="{{ $barang->id }}">{{ $barang->nama_barang }} (Stok: {{ $barang->stok }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" min="1" name="items[0][qty]" class="form-control" placeholder="Qty" required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="items[0][catatan]" class="form-control" placeholder="Catatan item (opsional)">
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="button" class="btn btn-outline-light btn-sm" id="addItemBtn">Tambah Item</button>
                <button type="submit" class="btn btn-primary btn-sm">Simpan Pesanan</button>
            </div>
        </form>
    </div>
</div>
@endif

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="mb-0">Daftar Pesanan</h5>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('orders.index') }}" class="btn btn-sm {{ request('status') ? 'btn-outline-light' : 'btn-primary' }}">Semua</a>
            <a href="{{ route('orders.index', ['status' => 'pending']) }}" class="btn btn-sm {{ request('status') === 'pending' ? 'btn-primary' : 'btn-outline-light' }}">Pending</a>
            <a href="{{ route('orders.index', ['status' => 'waiting_restock']) }}" class="btn btn-sm {{ request('status') === 'waiting_restock' ? 'btn-primary' : 'btn-outline-light' }}">Menunggu Restock</a>
            <a href="{{ route('orders.index', ['status' => 'reserved']) }}" class="btn btn-sm {{ request('status') === 'reserved' ? 'btn-primary' : 'btn-outline-light' }}">Reserved</a>
            <a href="{{ route('orders.index', ['status' => 'picking']) }}" class="btn btn-sm {{ request('status') === 'picking' ? 'btn-primary' : 'btn-outline-light' }}">Picking</a>
            <a href="{{ route('orders.index', ['status' => 'packed']) }}" class="btn btn-sm {{ request('status') === 'packed' ? 'btn-primary' : 'btn-outline-light' }}">Packed</a>
            <a href="{{ route('orders.index', ['status' => 'shipped']) }}" class="btn btn-sm {{ request('status') === 'shipped' ? 'btn-primary' : 'btn-outline-light' }}">Dikirim</a>
            <a href="{{ route('orders.index', ['status' => 'completed']) }}" class="btn btn-sm {{ request('status') === 'completed' ? 'btn-primary' : 'btn-outline-light' }}">Selesai</a>
        </div>
    </div>
    <div class="card-body orders-table-wrap">
        <table class="table table-sm align-middle">
            <thead>
            <tr>
                <th>No Pesanan</th>
                <th>Customer</th>
                <th>Tanggal</th>
                <th>Sumber</th>
                <th>Qty</th>
                <th>Total</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse($orders as $order)
                @php
                    $reservedQty = (int) $order->stockReservations->where('status', 'active')->sum('qty_reserved');
                    $badge = match($order->status_pesanan) {
                        'reserved' => 'text-bg-warning',
                        'picking' => 'text-bg-info',
                        'packed' => 'text-bg-secondary',
                        'shipped' => 'text-bg-primary',
                        'completed' => 'text-bg-success',
                        'waiting_restock' => 'text-bg-danger',
                        'rejected' => 'text-bg-dark',
                        default => 'text-bg-light text-dark',
                    };
                    $paymentStatus = (string) ($order->payment_status ?? 'unpaid');
                    $paymentLabel = match($paymentStatus) {
                        'awaiting_verification' => 'Menunggu Verifikasi',
                        'verified' => 'Terverifikasi',
                        'rejected' => 'Ditolak',
                        default => 'Belum Upload',
                    };
                    $paymentBadge = match($paymentStatus) {
                        'awaiting_verification' => 'text-bg-warning',
                        'verified' => 'text-bg-success',
                        'rejected' => 'text-bg-danger',
                        default => 'text-bg-secondary',
                    };
                @endphp
                <tr>
                    <td class="fw-semibold">{{ $order->nomor_pesanan }}</td>
                    <td>{{ optional($order->customer)->nama_customer ?? '-' }}</td>
                    <td>{{ optional($order->tanggal_pesanan)->format('d/m/Y H:i') }}</td>
                    <td>
                        @if(($order->order_source ?? 'admin_manual') === 'customer_portal')
                            <span class="badge text-bg-info">Portal</span>
                        @else
                            <span class="badge text-bg-secondary">Admin Manual</span>
                        @endif
                    </td>
                    <td>
                        <div>{{ $order->total_qty }}</div>
                        @if($reservedQty > 0)
                            <small class="text-warning">Reserved: {{ $reservedQty }}</small>
                        @endif
                    </td>
                    <td>Rp{{ number_format($order->total_nilai, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge {{ $badge }}">{{ order_status_label($order->status_pesanan) }}</span>
                    </td>
                    <td>
                        <div class="d-flex gap-1 flex-wrap order-actions">
                            @if($canSales && in_array($order->status_pesanan, ['pending', 'waiting_restock']))
                                <form method="POST" action="{{ route('orders.approve', $order) }}">@csrf<button class="btn btn-success btn-sm">Reserve</button></form>
                                <form method="POST" action="{{ route('orders.reject', $order) }}">@csrf<button class="btn btn-danger btn-sm">Reject</button></form>
                            @endif

                            @if($canWarehouse && $order->status_pesanan === 'reserved')
                                <form method="POST" action="{{ route('orders.picking', $order) }}">@csrf<button class="btn btn-outline-info btn-sm">Start Picking</button></form>
                                <form method="POST" action="{{ route('orders.pack', $order) }}">@csrf<button class="btn btn-warning btn-sm">Pack</button></form>
                            @endif

                            @if($canWarehouse && $order->status_pesanan === 'picking')
                                <form method="POST" action="{{ route('orders.pack', $order) }}">@csrf<button class="btn btn-warning btn-sm">Pack</button></form>
                            @endif

                            @if($canShipping && $order->status_pesanan === 'packed')
                                <form method="POST" action="{{ route('orders.ship', $order) }}" class="d-flex gap-1 flex-wrap">
                                    @csrf
                                    <input type="text" name="metode_kirim" class="form-control form-control-sm" placeholder="Metode" required>
                                    <input type="text" name="nama_ekspedisi" class="form-control form-control-sm" placeholder="Ekspedisi" required>
                                    <input type="text" name="no_resi" class="form-control form-control-sm" placeholder="No Resi" required>
                                    <button class="btn btn-primary btn-sm">Kirim</button>
                                </form>
                            @endif

                            @if($canShipping && $order->status_pesanan === 'shipped')
                                <span class="badge text-bg-info">Menunggu Konfirmasi</span>
                            @endif

                            @if($canSales && $order->status_pesanan !== 'completed')
                                <form method="POST" action="{{ route('orders.destroy', $order) }}" onsubmit="return confirm('Hapus pesanan {{ $order->nomor_pesanan }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm">Hapus</button>
                                </form>
                            @endif

                            @if($canSales && $paymentStatus === 'awaiting_verification')
                                <form method="POST" action="{{ route('orders.payment.verify', $order) }}">
                                    @csrf
                                    <button class="btn btn-success btn-sm">Verif Bayar</button>
                                </form>
                                <form method="POST" action="{{ route('orders.payment.reject', $order) }}" class="d-flex gap-1">
                                    @csrf
                                    <input type="text" name="payment_reject_note" class="form-control form-control-sm" placeholder="Alasan tolak" required>
                                    <button class="btn btn-outline-danger btn-sm">Tolak Bayar</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="8" class="pt-0">
                        <div class="small text-muted">Item:
                            @foreach($order->items as $it)
                                <span class="badge text-bg-dark border me-1" style="border-color:#3a4355!important;">
                                    {{ optional($it->dataBarang)->nama_barang }} x{{ $it->qty }}
                                </span>
                            @endforeach
                        </div>
                        @if(filled($order->catatan))
                            <div class="small mt-1">
                                <span class="text-warning">Catatan customer:</span>
                                <span class="text-muted">{{ $order->catatan }}</span>
                            </div>
                        @endif
                        <div class="small mt-1">
                            <span class="text-warning">Pembayaran:</span>
                            <span class="badge {{ $paymentBadge }}">{{ $paymentLabel }}</span>
                            @if(filled($order->payment_proof_path))
                                <a href="{{ route('orders.payment.proof.view', $order) }}" target="_blank" rel="noopener" class="ms-1">Lihat bukti</a>
                            @endif
                        </div>
                        @if(filled($order->payment_note))
                            <div class="small text-muted">Catatan bayar customer: {{ $order->payment_note }}</div>
                        @endif
                        @if(filled($order->payment_reject_note))
                            <div class="small text-danger">Alasan tolak pembayaran: {{ $order->payment_reject_note }}</div>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-muted">Belum ada pesanan.</td></tr>
            @endforelse
            </tbody>
        </table>
        <div class="mt-2">{{ $orders->links() }}</div>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        const addBtn = document.getElementById('addItemBtn');
        const wrap = document.getElementById('itemsWrap');
        if (!addBtn || !wrap) return;

        let idx = 1;
        addBtn.addEventListener('click', function () {
            const row = document.createElement('div');
            row.className = 'row g-2 item-row';
            row.innerHTML = `
                <div class="col-md-6">
                    <select name="items[${idx}][data_barang_id]" class="form-select" required>
                        <option value="">Pilih barang</option>
                        @foreach($barangs as $barang)
                            <option value="{{ $barang->id }}">{{ $barang->nama_barang }} (Stok: {{ $barang->stok }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" min="1" name="items[${idx}][qty]" class="form-control" placeholder="Qty" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="items[${idx}][catatan]" class="form-control" placeholder="Catatan item (opsional)">
                </div>
                <div class="col-md-1 d-grid">
                    <button type="button" class="btn btn-outline-danger btn-sm remove-row">X</button>
                </div>
            `;
            wrap.appendChild(row);
            idx++;
        });

        wrap.addEventListener('click', function (e) {
            const btn = e.target.closest('.remove-row');
            if (!btn) return;
            const row = btn.closest('.item-row');
            if (row) row.remove();
        });
    })();
</script>
@endpush
@endsection
