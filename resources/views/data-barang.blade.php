@extends('admin.master-admin')

@section('title', 'Data Barang')

@section('content')
    <div class="page-heading">
        <h3>{{ !empty($isStokView) ? 'Monitor Stok' : 'Katalog Gudang' }}</h3>
        <p class="text-muted mb-0">
            {{ !empty($isStokView) ? 'Pantau jumlah stok, stok habis, dan stok menipis.' : 'Kelola katalog produk dan detail barang.' }}
        </p>
    </div>

    @if(empty($isStokView) && auth()->user() && auth()->user()->canManageWarehouse())
    <div class="mb-4">
        <button type="button" class="btn btn-primary" onclick="toggleForm()" id="toggleBtn">
            <i class="bi bi-plus-circle"></i> Tambah Barang Baru
        </button>
        @if(isset($editRow))
            <a href="{{ route('barang.index') }}" class="btn btn-secondary">
                <i class="bi bi-x-circle"></i> Batal Edit
            </a>
        @endif
    </div>
    @endif

    @if(empty($isStokView))
    <div class="card mb-4 {{ isset($editRow) ? '' : 'd-none' }}" id="productForm">
        <div class="card-header">
            <h5 class="mb-0">{{ isset($editRow) ? 'Edit Barang' : 'Tambah Barang Baru' }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ isset($editRow) ? route('barang.update', $editRow->id) : route('barang.store') }}"
                  method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($editRow))
                    @method('PUT')
                @endif

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="kode_barang" class="form-label">Kode Barang</label>
                            <input type="text" name="kode_barang" id="kode_barang" class="form-control" placeholder="Contoh: ELX-0001"
                                   value="{{ old('kode_barang', $editRow->kode_barang ?? '') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="merek" class="form-label">Merek</label>
                            <input type="text" name="merek" id="merek" class="form-control" placeholder="Contoh: Panasonic"
                                   value="{{ old('merek', $editRow->merek ?? '') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="tipe_model" class="form-label">Tipe / Model</label>
                            <input type="text" name="tipe_model" id="tipe_model" class="form-control" placeholder="Contoh: TX-200"
                                   value="{{ old('tipe_model', $editRow->tipe_model ?? '') }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nama_barang" class="form-label">Nama Barang</label>
                            <input type="text" name="nama_barang" id="nama_barang" class="form-control" placeholder="Masukkan nama barang"
                                   value="{{ old('nama_barang', $editRow->nama_barang ?? '') }}" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="stok" class="form-label">Stok</label>
                            <input type="number" name="stok" id="stok" class="form-control" placeholder="0"
                                   value="{{ old('stok', $editRow->stok ?? '') }}" required min="0">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="stok_minimum" class="form-label">Stok Minimum</label>
                            <input type="number" name="stok_minimum" id="stok_minimum" class="form-control" placeholder="5"
                                   value="{{ old('stok_minimum', $editRow->stok_minimum ?? 5) }}" min="0">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga Dasar</label>
                            <input type="number" step="0.01" name="harga" id="harga" class="form-control" placeholder="0"
                                   value="{{ old('harga', $editRow->harga ?? '') }}" required min="0">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="harga_beli" class="form-label">Harga Beli</label>
                            <input type="number" step="0.01" name="harga_beli" id="harga_beli" class="form-control" placeholder="0"
                                   value="{{ old('harga_beli', $editRow->harga_beli ?? '') }}" min="0">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="harga_jual" class="form-label">Harga Jual</label>
                            <input type="number" step="0.01" name="harga_jual" id="harga_jual" class="form-control" placeholder="0"
                                   value="{{ old('harga_jual', $editRow->harga_jual ?? '') }}" min="0">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="satuan" class="form-label">Satuan</label>
                            <input type="text" name="satuan" id="satuan" class="form-control" placeholder="unit"
                                   value="{{ old('satuan', $editRow->satuan ?? 'unit') }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="kategori_id" class="form-label">Kategori</label>
                            <select name="kategori_id" id="kategori_id" class="form-select">
                                <option value="">Pilih Kategori (opsional)</option>
                                @foreach($kategoriList as $k)
                                    <option value="{{ $k->id }}" {{ old('kategori_id', $editRow->kategori_id ?? '') == $k->id ? 'selected' : '' }}>
                                        {{ $k->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="garansi_bulan" class="form-label">Garansi (Bulan)</label>
                                    <input type="number" name="garansi_bulan" id="garansi_bulan" class="form-control" placeholder="12"
                                           value="{{ old('garansi_bulan', $editRow->garansi_bulan ?? 0) }}" min="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="lokasi_rak" class="form-label">Lokasi Rak</label>
                                    <input type="text" name="lokasi_rak" id="lokasi_rak" class="form-control" placeholder="A-01-03"
                                           value="{{ old('lokasi_rak', $editRow->lokasi_rak ?? '') }}">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="foto_barang" class="form-label">Foto Barang</label>
                            <input type="file" name="foto_barang" id="foto_barang" class="form-control" accept="image/*">
                        </div>
                    </div>
                </div>

                @if(isset($editRow))
                    <div class="mb-3">
                        <label class="form-label">Foto Saat Ini</label>
                        <div>
                            @php
                                $placeholderImg = 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="200" height="150" viewBox="0 0 200 150"><rect fill="#e0e0e0" width="200" height="150"/><text x="50%" y="50%" text-anchor="middle" dy=".3em" fill="#999" font-family="Arial" font-size="14">No Image</text></svg>');
                                $currentFoto = $editRow->foto_url ?? $placeholderImg;
                            @endphp
                            <img src="{{ $currentFoto }}" width="120" alt="Foto" class="img-thumbnail" style="background-color: #f5f5f5;" onerror="this.src='{{ $placeholderImg }}'; this.style.opacity='1';">
                        </div>
                    </div>
                @endif

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> {{ isset($editRow) ? 'Perbarui' : 'Simpan' }}
                    </button>
                    @if(isset($editRow))
                        <a href="{{ route('barang.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Batal
                        </a>
                    @else
                        <button type="button" class="btn btn-secondary" onclick="toggleForm()">
                            <i class="bi bi-x-circle"></i> Batal
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
    @endif

    @if(empty($isStokView))
    <script>
        function toggleForm() {
            const form = document.getElementById('productForm');
            const btn = document.getElementById('toggleBtn');
            
            if (form.classList.contains('d-none')) {
                form.classList.remove('d-none');
                btn.innerHTML = '<i class="bi bi-x-circle"></i> Tutup Form';
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-secondary');
            } else {
                form.classList.add('d-none');
                btn.innerHTML = '<i class="bi bi-plus-circle"></i> Tambah Barang Baru';
                btn.classList.remove('btn-secondary');
                btn.classList.add('btn-primary');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('.ratio img');
            images.forEach(function(img) {
                img.addEventListener('error', function() {
                    if (!this.src.includes('product-placeholder.png')) {
                        this.src = '{{ asset("assets/static/images/samples/product-placeholder.png") }}';
                    }
                });
            });
        });
    </script>
    @endif


    <div class="mt-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h4 class="mb-0">{{ !empty($isStokView) ? 'Ringkasan Stok Barang' : 'Katalog Barang' }}</h4>
            <div class="text-muted small">
                @if(!empty($isStokView))
                    Total stok: {{ number_format($stockSummary['total_stok'] ?? 0) }} unit
                @else
                    Total: {{ $barang->total() }} item ({{ $barang->count() }} ditampilkan)
                @endif
            </div>
        </div>

        @if(!empty($isStokView))
            <div class="row g-3 mb-3">
                <div class="col-12 col-md-3">
                    <div class="card p-3 h-100">
                        <div class="text-muted small">Total Item</div>
                        <div class="fs-4 fw-bold">{{ number_format($stockSummary['total_item'] ?? 0) }}</div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="card p-3 h-100">
                        <div class="text-muted small">Stok Reserved</div>
                        <div class="fs-4 fw-bold text-info">{{ number_format($stockSummary['stok_reserved'] ?? 0) }}</div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="card p-3 h-100">
                        <div class="text-muted small">Stok Tersedia</div>
                        <div class="fs-4 fw-bold text-success">{{ number_format($stockSummary['stok_tersedia'] ?? 0) }}</div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="card p-3 h-100">
                        <div class="text-muted small">Stok Menipis (<= stok minimum)</div>
                        <div class="fs-4 fw-bold text-warning">{{ number_format($stockSummary['stok_menipis'] ?? 0) }}</div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="card p-3 h-100">
                        <div class="text-muted small">Stok Habis</div>
                        <div class="fs-4 fw-bold text-danger">{{ number_format($stockSummary['stok_habis'] ?? 0) }}</div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row g-3">
            @forelse($barang as $item)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="card h-100 shadow-sm border-0 {{ $item->stok > 0 && $item->stok <= ($item->stok_minimum ?? 5) ? 'stock-alert' : '' }}" style="border-radius: 12px; overflow: hidden;">
                        @php
                            $imgSrc = $item->foto_url;
                            $placeholder = 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="400" height="300" viewBox="0 0 400 300"><rect fill="#e0e0e0" width="400" height="300"/><text x="50%" y="50%" text-anchor="middle" dy=".3em" fill="#999" font-family="Arial" font-size="20">No Image</text></svg>');
                        @endphp
                        <div class="bg-light position-relative" style="height: 200px; overflow: hidden;">
                            <img src="{{ $imgSrc }}" 
                                 class="w-100 h-100" 
                                 alt="{{ $item->nama_barang }}" 
                                 style="object-fit: cover; background-color: #f5f5f5;"
                                 onerror="if(this.src !== '{{ $placeholder }}') { this.src='{{ $placeholder }}'; }">
                        </div>
                        <div class="card-body d-flex flex-column" style="min-height: 150px;">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <h6 class="card-title mb-0 fw-bold" style="line-height:1.2; font-size:1.05rem; flex: 1; margin-right: 8px;">{{ $item->nama_barang }}</h6>
                                @if($item->stok > 0)
                                    <span class="badge {{ ($item->stok > 0 && $item->stok <= ($item->stok_minimum ?? 5)) ? 'bg-warning text-dark' : 'bg-success' }}" style="flex-shrink: 0;">
                                        Stok {{ $item->stok }}
                                    </span>
                                @else
                                    <span class="badge bg-danger" style="flex-shrink: 0;">Habis</span>
                                @endif
                            </div>
                            <div class="text-muted small mb-1">{{ optional($item->kategori)->nama_kategori ?? 'Tanpa Kategori' }}</div>
                            <div class="text-muted small mb-2">{{ $item->merek ?? 'No Brand' }} {{ $item->kode_barang ? 'â€¢ '.$item->kode_barang : '' }}</div>
                            <div class="fw-semibold mb-2 mono-price">Rp{{ number_format($item->harga, 0, ',', '.') }}</div>

                            <div class="mt-auto d-flex gap-2 pt-2">
                                @if(!empty($isStokView))
                                    <button type="button"
                                            class="btn btn-outline-info btn-sm w-100"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#stokDetailPanel{{ $item->id }}"
                                            aria-expanded="false"
                                            aria-controls="stokDetailPanel{{ $item->id }}">
                                        Detail Stok
                                    </button>
                                @elseif(auth()->user() && auth()->user()->canManageWarehouse())
                                    <div class="w-100 d-grid gap-2">
                                        <button type="button" class="btn btn-outline-light btn-sm w-100" data-bs-toggle="collapse" data-bs-target="#stockOutPanel{{ $item->id }}" aria-expanded="false" aria-controls="stockOutPanel{{ $item->id }}" {{ $item->stok <= 0 ? 'disabled' : '' }}>
                                            Barang Keluar
                                        </button>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('barang.index', ['edit' => $item->id]) }}" class="btn btn-warning btn-sm flex-fill">Ubah</a>
                                            @if(auth()->user()->isSuperAdmin())
                                                <form action="{{ route('barang.destroy', $item->id) }}" method="POST" class="m-0 p-0 flex-fill">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm w-100" onclick="return confirm('Yakin mau hapus barang ini?')">Hapus</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                @endif
                            </div>

                            @if(!empty($isStokView))
                                @php
                                    $stokStatus = $item->stok <= 0 ? 'Stok Habis' : (($item->stok <= ($item->stok_minimum ?? 5)) ? 'Stok Menipis' : 'Stok Aman');
                                    $stokBadgeClass = $item->stok <= 0 ? 'bg-danger' : (($item->stok <= ($item->stok_minimum ?? 5)) ? 'bg-warning text-dark' : 'bg-success');
                                @endphp
                                <div class="collapse mt-2" id="stokDetailPanel{{ $item->id }}">
                                    <div class="border rounded p-2">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <div>
                                                <div class="fw-semibold">{{ $item->nama_barang }}</div>
                                                <div class="text-muted small">{{ optional($item->kategori)->nama_kategori ?? 'Tanpa Kategori' }}</div>
                                            </div>
                                            <span class="badge {{ $stokBadgeClass }}">{{ $stokStatus }}</span>
                                        </div>
                                        <div class="small text-muted">Jumlah: {{ number_format($item->stok) }} unit</div>
                                        <div class="small text-muted">Harga: Rp{{ number_format($item->harga, 0, ',', '.') }}</div>
                                        <div class="small text-muted">Nilai Stok: Rp{{ number_format($item->stok * $item->harga, 0, ',', '.') }}</div>
                                        <div class="small text-muted">Update: {{ optional($item->updated_at)->format('d/m/Y H:i') ?? '-' }}</div>
                                    </div>
                                </div>
                            @endif

                            @if(empty($isStokView) && auth()->user() && auth()->user()->canManageWarehouse())
                                <div class="collapse mt-2" id="stockOutPanel{{ $item->id }}">
                                    <div class="border rounded p-2">
                                        <form action="{{ route('barang.stock-out', $item->id) }}" method="POST">
                                            @csrf
                                            <div class="small fw-semibold">{{ $item->nama_barang }}</div>
                                            <div class="small text-muted mb-2">Stok saat ini: {{ $item->stok }} unit</div>
                                            <div class="mb-2">
                                                <label class="form-label small mb-1">Jumlah Keluar</label>
                                                <input type="number" name="qty_keluar" class="form-control form-control-sm" min="1" max="{{ $item->stok }}" required>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small mb-1">Catatan</label>
                                                <textarea name="catatan_keluar" class="form-control form-control-sm" rows="2" placeholder="Opsional"></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-danger btn-sm w-100" {{ $item->stok <= 0 ? 'disabled' : '' }}>Proses Barang Keluar</button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-light border">Belum ada barang.</div>
                </div>
            @endforelse
        </div>
        
        @if($barang->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $barang->links() }}
        </div>
        @endif
    </div>

    <div class="d-none">
        <table class="table mt-4">
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nama Barang</th>
                    <th>Stok</th>
                    <th>Harga</th>
                    <th>Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($barang as $item)
                <tr>
                    <td>
                    @php
                        $placeholderImgTable = 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="400" height="200" viewBox="0 0 400 200"><rect fill="#e0e0e0" width="400" height="200"/><text x="50%" y="50%" text-anchor="middle" dy=".3em" fill="#999" font-family="Arial" font-size="16">No Image</text></svg>');
                        $tableImgSrc = $item->foto_url ?? $placeholderImgTable;
                    @endphp
                    <img src="{{ $tableImgSrc }}" 
         alt="{{ $item->nama_barang }}" 
         style="width:100%; height:200px; object-fit:cover; border-radius:8px; background-color:#f5f5f5;"
         onerror="this.onerror=null; this.src='{{ $placeholderImgTable }}';">

                    </td>
                    <td>{{ $item->nama_barang }}</td>
                    <td>{{ $item->stok }}</td>
                    <td>Rp{{ number_format($item->harga, 2, '.', ',') }}</td>
                    <td>{{ optional($item->kategori)->nama_kategori }}</td>
                    <td>
                        @if(auth()->user() && auth()->user()->canManageWarehouse())
                            <a href="{{ route('barang.index', ['edit' => $item->id]) }}" class="btn btn-warning btn-sm">Ubah</a>
                            @if(auth()->user()->isSuperAdmin())
                                <form action="{{ route('barang.destroy', $item->id) }}" method="POST" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin mau hapus barang ini?')">Hapus</button>
                                </form>
                            @endif
                        @else
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <style>
        .mono-price {
            font-size: 1.05rem;
            color: #fb923c;
            font-family: 'JetBrains Mono', monospace;
        }

        .stock-alert {
            border-left: 3px solid rgba(245, 158, 11, 0.85) !important;
            background: linear-gradient(180deg, rgba(245, 158, 11, 0.06), rgba(24, 28, 34, 0.92));
        }

        .stock-alert:hover {
            box-shadow: 0 10px 22px rgba(245, 158, 11, 0.15) !important;
        }
    </style>
@endsection                       
