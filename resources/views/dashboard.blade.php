@extends('admin.master-admin')

@section('title', 'Dashboard RakElektronik')

@section('content')
@php
    $totalBarang = \App\Models\DataBarang::count();
    $stokMenipis = \App\Models\DataBarang::whereColumn('stok', '<=', 'stok_minimum')->where('stok', '>', 0)->count();
    $stokHabis = \App\Models\DataBarang::where('stok', 0)->count();
    $barangMasukHariIni = \App\Models\InventoryLog::whereIn('action', ['create', 'stock_in'])->whereDate('created_at', today())->count();
    $barangKeluarHariIni = \App\Models\InventoryLog::where('action', 'stock_out')->whereDate('created_at', today())->count();
    $totalNilaiStok = \App\Models\DataBarang::query()->selectRaw('COALESCE(SUM(stok * harga), 0) as total')->value('total');
    $orderPending = \App\Models\Order::whereIn('status_pesanan', ['pending', 'waiting_restock'])->count();
    $orderReserved = \App\Models\Order::where('status_pesanan', 'reserved')->count();
    $orderPicking = \App\Models\Order::where('status_pesanan', 'picking')->count();
    $orderPacked = \App\Models\Order::where('status_pesanan', 'packed')->count();
    $orderShipped = \App\Models\Order::where('status_pesanan', 'shipped')->count();

    $dates = collect(range(6, 0))->map(fn($i) => now()->subDays($i));
    $dailyLabels = $dates->map(fn($d) => $d->format('d M'))->values();
    $dailyMasuk = $dates->map(fn($d) => \App\Models\InventoryLog::whereIn('action', ['create', 'stock_in'])->whereDate('created_at', $d)->count())->values();
    $dailyKeluar = $dates->map(fn($d) => \App\Models\InventoryLog::where('action', 'stock_out')->whereDate('created_at', $d)->count())->values();

    $stokByKategori = \App\Models\DataBarang::query()
        ->leftJoin('kategori_barangs', 'data_barangs.kategori_id', '=', 'kategori_barangs.id')
        ->selectRaw('COALESCE(kategori_barangs.nama_kategori, "Tanpa Kategori") as kategori, COALESCE(SUM(data_barangs.stok),0) as total_stok')
        ->groupBy('kategori')
        ->orderByDesc('total_stok')
        ->limit(6)
        ->get();

    $stokKritis = \App\Models\DataBarang::with('kategori')
        ->orderBy('stok')
        ->limit(8)
        ->get();

    $aktivitasTerbaru = \App\Models\InventoryLog::with(['user', 'dataBarang'])
        ->latest()
        ->limit(8)
        ->get();
@endphp

<div class="ops-top mb-3">
    <div>
        <h2 class="mb-1">Pusat Kendali Gudang</h2>
        <p class="text-muted mb-0">Ringkasan operasional stok gudang hari ini.</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('barang.index') }}" class="btn btn-primary btn-sm">Tambah Barang</a>
        <a href="{{ route('inventory.logs', ['type' => 'keluar']) }}" class="btn btn-outline-warning btn-sm">Barang Keluar</a>
        <a href="{{ route('laporan.barang') }}" class="btn btn-outline-light btn-sm">Laporan</a>
    </div>
</div>

<div class="kpi-grid mb-3">
    <div class="kpi-box">
        <span>Total SKU</span>
        <h3>{{ number_format($totalBarang) }}</h3>
        <small class="kpi-tag ok">Aman</small>
    </div>
    <div class="kpi-box">
        <span>Stok Menipis</span>
        <h3>{{ number_format($stokMenipis) }}</h3>
        <small class="kpi-tag warn">Perlu Restock</small>
    </div>
    <div class="kpi-box">
        <span>Stok Habis</span>
        <h3>{{ number_format($stokHabis) }}</h3>
        <small class="kpi-tag danger">Prioritas</small>
    </div>
    <div class="kpi-box">
        <span>Nilai Inventaris</span>
        <h3>Rp{{ number_format($totalNilaiStok, 0, ',', '.') }}</h3>
        <small class="kpi-tag info">Aktif</small>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Antrian Operasional</h5>
        <small class="text-muted">Order pipeline realtime</small>
    </div>
    <div class="card-body">
        <div class="kpi-grid">
            <div class="kpi-box">
                <span>Order Pending</span>
                <h3>{{ number_format($orderPending) }}</h3>
                <small class="kpi-tag warn">Perlu Tindakan</small>
            </div>
            <div class="kpi-box">
                <span>Reserved</span>
                <h3>{{ number_format($orderReserved) }}</h3>
                <small class="kpi-tag info">Siap Picking</small>
            </div>
            <div class="kpi-box">
                <span>Picking</span>
                <h3>{{ number_format($orderPicking) }}</h3>
                <small class="kpi-tag info">Proses Gudang</small>
            </div>
            <div class="kpi-box">
                <span>Packed / Shipped</span>
                <h3>{{ number_format($orderPacked + $orderShipped) }}</h3>
                <small class="kpi-tag ok">Siap Antar</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-12 col-xl-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Pergerakan Barang 7 Hari</h5>
                <span class="badge text-bg-dark border" style="border-color:#3a4355!important;">Masuk vs Keluar</span>
            </div>
            <div class="card-body">
                <canvas id="movementChart" height="110"></canvas>
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Distribusi Stok</h5>
                <span class="badge text-bg-dark border" style="border-color:#3a4355!important;">Kategori</span>
            </div>
            <div class="card-body">
                <canvas id="categoryChart" height="170"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mt-1">
    <div class="col-12 col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Barang Kritis</h5>
                <small class="text-muted">Prioritas cek stok</small>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead>
                    <tr>
                        <th>Barang</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($stokKritis as $item)
                        @php
                            $minimum = (int) ($item->stok_minimum ?? 5);
                            $status = $item->stok <= 0 ? 'Habis' : ($item->stok <= $minimum ? 'Menipis' : 'Aman');
                            $statusClass = $item->stok <= 0 ? 'text-bg-danger' : ($item->stok <= $minimum ? 'text-bg-warning' : 'text-bg-success');
                        @endphp
                        <tr class="{{ $item->stok > 0 && $item->stok <= $minimum ? 'row-alert' : '' }}">
                            <td class="fw-semibold">{{ $item->nama_barang }}</td>
                            <td>{{ optional($item->kategori)->nama_kategori ?? '-' }}</td>
                            <td>{{ $item->stok }}</td>
                            <td><span class="badge {{ $statusClass }}">{{ $status }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-muted">Belum ada data.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Aktivitas Terkini</h5>
                <small class="text-muted">Riwayat terbaru</small>
            </div>
            <div class="card-body">
                <div class="feed-grid">
                    @forelse($aktivitasTerbaru as $log)
                        <div class="feed-item">
                            <div>
                                <div class="fw-semibold">{{ optional($log->dataBarang)->nama_barang ?? 'Barang dihapus' }}</div>
                                <small class="text-muted">{{ optional($log->user)->name ?? 'System' }} · {{ $log->created_at->diffForHumans() }}</small>
                            </div>
                            <span class="badge {{ in_array($log->action, ['stock_out','delete']) ? 'text-bg-danger' : 'text-bg-success' }}">{{ strtoupper($log->action) }}</span>
                        </div>
                    @empty
                        <div class="text-muted">Belum ada aktivitas.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .ops-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 0.75rem;
    }

    .kpi-box {
        background: #1a1f28;
        border: 1px solid #2e3747;
        border-left: 4px solid #f97316;
        border-radius: 10px;
        padding: 0.9rem;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.22);
    }

    .kpi-box span {
        color: #9ca8b8;
        font-size: 0.78rem;
        display: block;
        margin-bottom: 0.2rem;
    }

    .kpi-box h3 {
        margin: 0;
        font-size: 1.35rem;
        font-family: 'Poppins', sans-serif;
    }

    .kpi-tag {
        margin-top: 0.45rem;
        display: inline-block;
        border-radius: 999px;
        padding: 2px 8px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .kpi-tag.ok { background: rgba(34, 197, 94, 0.18); color: #86efac; }
    .kpi-tag.warn { background: rgba(245, 158, 11, 0.18); color: #fcd34d; }
    .kpi-tag.danger { background: rgba(239, 68, 68, 0.18); color: #fca5a5; }
    .kpi-tag.info { background: rgba(249, 115, 22, 0.18); color: #fdba74; }

    .feed-grid { display: grid; gap: 0.6rem; }

    .feed-item {
        display: flex;
        justify-content: space-between;
        gap: 0.7rem;
        align-items: center;
        border: 1px solid #2e3747;
        border-radius: 10px;
        padding: 0.6rem 0.7rem;
        background: rgba(20, 24, 33, 0.55);
    }

    .row-alert td {
        border-left: 3px solid rgba(245, 158, 11, 0.8);
        background: rgba(245, 158, 11, 0.05);
    }

    @media (max-width: 1199px) {
        .kpi-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }

    @media (max-width: 767px) {
        .kpi-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    (function () {
        const labels = @json($dailyLabels);
        const dailyMasuk = @json($dailyMasuk);
        const dailyKeluar = @json($dailyKeluar);
        const stokKategoriLabel = @json($stokByKategori->pluck('kategori')->values());
        const stokKategoriData = @json($stokByKategori->pluck('total_stok')->values());

        const movementCtx = document.getElementById('movementChart');
        if (movementCtx) {
            new Chart(movementCtx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [
                        {
                            label: 'Masuk',
                            data: dailyMasuk,
                            borderColor: '#22c55e',
                            backgroundColor: 'rgba(34, 197, 94, 0.12)',
                            fill: true,
                            tension: 0.35,
                            borderWidth: 2
                        },
                        {
                            label: 'Keluar',
                            data: dailyKeluar,
                            borderColor: '#f97316',
                            backgroundColor: 'rgba(249, 115, 22, 0.12)',
                            fill: true,
                            tension: 0.35,
                            borderWidth: 2
                        }
                    ]
                },
                options: {
                    plugins: {
                        legend: { labels: { color: '#cfd7e6' } }
                    },
                    scales: {
                        x: { ticks: { color: '#9ca8b8' }, grid: { color: 'rgba(156,168,184,0.16)' } },
                        y: { ticks: { color: '#9ca8b8', precision: 0 }, grid: { color: 'rgba(156,168,184,0.16)' } }
                    }
                }
            });
        }

        const categoryCtx = document.getElementById('categoryChart');
        if (categoryCtx) {
            new Chart(categoryCtx, {
                type: 'doughnut',
                data: {
                    labels: stokKategoriLabel,
                    datasets: [{
                        data: stokKategoriData,
                        backgroundColor: ['#f97316', '#22c55e', '#f59e0b', '#a855f7', '#38bdf8', '#f43f5e'],
                        borderColor: '#111317',
                        borderWidth: 2
                    }]
                },
                options: {
                    plugins: {
                        legend: { position: 'bottom', labels: { color: '#cfd7e6', boxWidth: 12, boxHeight: 12 } }
                    }
                }
            });
        }
    })();
</script>
@endpush
