@extends('admin.master-admin')

@section('title', 'Laporan Barang')

@section('content')
    <div class="page-heading">
        <h3>Laporan Barang</h3>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5>Filter Laporan</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('laporan.barang') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" class="form-control" value="{{ request('tanggal_mulai') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tanggal Akhir</label>
                        <input type="date" name="tanggal_akhir" class="form-control" value="{{ request('tanggal_akhir') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Kategori</label>
                        <select name="kategori_id" class="form-select">
                            <option value="">Semua Kategori</option>
                            @foreach(\App\Models\KategoriBarang::all() as $kategori)
                            <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <ul class="nav nav-tabs" id="reportTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="stok-tab" data-bs-toggle="tab" data-bs-target="#stok" type="button" role="tab">
                Laporan Stok Barang
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pergerakan-tab" data-bs-toggle="tab" data-bs-target="#pergerakan" type="button" role="tab">
                Laporan Pergerakan Barang
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="rusak-tab" data-bs-toggle="tab" data-bs-target="#rusak" type="button" role="tab">
                Laporan Inventaris Rusak/Hilang
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="nilai-tab" data-bs-toggle="tab" data-bs-target="#nilai" type="button" role="tab">
                Laporan Nilai Aset
            </button>
        </li>
    </ul>

    <div class="tab-content" id="reportTabsContent">
        <div class="tab-pane fade show active" id="stok" role="tabpanel">
            <div class="card mt-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Laporan Stok Barang</h5>
                    <div>
                        <button class="btn btn-success btn-sm" onclick="exportReport('stok', 'pdf')">
                            <i class="bi bi-file-pdf"></i> PDF
                        </button>
                        <button class="btn btn-info btn-sm" onclick="exportReport('stok', 'excel')">
                            <i class="bi bi-file-excel"></i> Excel
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th>Kategori</th>
                                    <th>Stok Tersedia</th>
                                    <th>Harga Satuan</th>
                                    <th>Total Nilai</th>
                                    <th>Tanggal Update</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($barangs as $idx => $barang)
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td>{{ $barang->nama_barang }}</td>
                                    <td>{{ $barang->kategori->nama_kategori ?? '-' }}</td>
                                    <td>{{ $barang->stok }}</td>
                                    <td>Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($barang->stok * $barang->harga, 0, ',', '.') }}</td>
                                    <td>{{ $barang->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="pergerakan" role="tabpanel">
            <div class="card mt-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Laporan Pergerakan Barang (Masuk/Keluar)</h5>
                    <div>
                        <button class="btn btn-success btn-sm" onclick="exportReport('pergerakan', 'pdf')">
                            <i class="bi bi-file-pdf"></i> PDF
                        </button>
                        <button class="btn btn-info btn-sm" onclick="exportReport('pergerakan', 'excel')">
                            <i class="bi bi-file-excel"></i> Excel
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6>Barang Masuk</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Nama Barang</th>
                                            <th>Jumlah</th>
                                            <th>Supplier</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(\App\Models\DataBarang::whereDate('created_at', '>=', request('tanggal_mulai', now()->subDays(30)))
                                            ->whereDate('created_at', '<=', request('tanggal_akhir', now()))
                                            ->when(request('kategori_id'), function($query) {
                                                return $query->where('kategori_id', request('kategori_id'));
                                            })
                                            ->when(request('q'), function($query) {
                                                $keyword = trim((string) request('q'));
                                                return $query->where('nama_barang', 'like', '%' . $keyword . '%');
                                            })
                                            ->get() as $barang)
                                        <tr>
                                            <td>{{ $barang->created_at->format('d/m/Y') }}</td>
                                            <td>{{ $barang->nama_barang }}</td>
                                            <td>{{ $barang->stok }}</td>
                                            <td>{{ $barang->supplier ?? '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6>Barang Keluar</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Nama Barang</th>
                                            <th>Jumlah</th>
                                            <th>Penerima/Keperluan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">Belum ada data barang keluar</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="rusak" role="tabpanel">
            <div class="card mt-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Laporan Inventaris Rusak/Hilang</h5>
                    <div>
                        <button class="btn btn-success btn-sm" onclick="exportReport('rusak', 'pdf')">
                            <i class="bi bi-file-pdf"></i> PDF
                        </button>
                        <button class="btn btn-info btn-sm" onclick="exportReport('rusak', 'excel')">
                            <i class="bi bi-file-excel"></i> Excel
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Nama Barang</th>
                                    <th>Kategori</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Belum ada data barang rusak/hilang</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="nilai" role="tabpanel">
            <div class="card mt-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Laporan Nilai Aset</h5>
                    <div>
                        <button class="btn btn-success btn-sm" onclick="exportReport('nilai', 'pdf')">
                            <i class="bi bi-file-pdf"></i> PDF
                        </button>
                        <button class="btn btn-info btn-sm" onclick="exportReport('nilai', 'excel')">
                            <i class="bi bi-file-excel"></i> Excel
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h4>Total Aset</h4>
                                    <h3>Rp {{ number_format($barangs->sum(function($b) { return $b->stok * $b->harga; }), 0, ',', '.') }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h4>Jumlah Item</h4>
                                    <h3>{{ $barangs->count() }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h4>Rata-rata Nilai</h4>
                                    <h3>Rp {{ number_format($barangs->avg(function($b) { return $b->stok * $b->harga; }), 0, ',', '.') }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h4>Kategori</h4>
                                    <h3>{{ $barangs->groupBy('kategori_id')->count() }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th>Kategori</th>
                                    <th>Stok</th>
                                    <th>Harga Satuan</th>
                                    <th>Total Nilai</th>
                                    <th>Persentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalNilai = $barangs->sum(function($b) { return $b->stok * $b->harga; });
                                @endphp
                                @foreach($barangs as $idx => $barang)
                                @php
                                    $nilaiBarang = $barang->stok * $barang->harga;
                                    $persentase = $totalNilai > 0 ? ($nilaiBarang / $totalNilai) * 100 : 0;
                                @endphp
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td>{{ $barang->nama_barang }}</td>
                                    <td>{{ $barang->kategori->nama_kategori ?? '-' }}</td>
                                    <td>{{ $barang->stok }}</td>
                                    <td>Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($nilaiBarang, 0, ',', '.') }}</td>
                                    <td>{{ number_format($persentase, 2) }}%</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function exportReport(type, format) {
            const params = new URLSearchParams(window.location.search);
            params.set('export', type);
            params.set('format', format);
            window.open('{{ route("laporan.barang") }}?' + params.toString(), '_blank');
        }
    </script>
@endsection


