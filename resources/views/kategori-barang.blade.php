@extends('admin.master-admin')

@section('title', 'Kategori Barang')

@section('content')
    <div class="page-heading">
        <h3>Category Control</h3>
        <p class="text-muted mb-0">Kelola klasifikasi barang untuk operasional gudang.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(auth()->user() && auth()->user()->canManageWarehouse())
    <div class="mb-4">
        <button type="button" class="btn btn-primary" onclick="toggleCategoryForm()" id="toggleCategoryBtn">
            <i class="bi bi-plus-circle"></i> Tambah Kategori Baru
        </button>
    </div>

    <div class="card mb-4" id="categoryForm" style="display: none;">
        <div class="card-header">
            <h5 class="mb-0">Tambah Kategori Baru</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('kategori.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nama_kategori" class="form-label">Nama Kategori</label>
                            <input type="text" name="nama_kategori" id="nama_kategori" class="form-control" placeholder="Masukkan nama kategori" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <input type="text" name="deskripsi" id="deskripsi" class="form-control" placeholder="Deskripsi kategori (opsional)">
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="toggleCategoryForm()">
                        <i class="bi bi-x-circle"></i> Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleCategoryForm() {
            const form = document.getElementById('categoryForm');
            const btn = document.getElementById('toggleCategoryBtn');
            
            if (form.style.display === 'none') {
                form.style.display = 'block';
                btn.innerHTML = '<i class="bi bi-x-circle"></i> Tutup Form';
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-secondary');
            } else {
                form.style.display = 'none';
                btn.innerHTML = '<i class="bi bi-plus-circle"></i> Tambah Kategori Baru';
                btn.classList.remove('btn-secondary');
                btn.classList.add('btn-primary');
            }
        }
    </script>
    @endif

    <div class="row mb-4">
        <div class="col-md-6">
            <label for="filter_kategori" class="form-label">Filter berdasarkan Kategori:</label>
            <form id="filterForm" method="GET" action="{{ route('kategori.barang') }}">
                <select id="filter_kategori" name="kategori_id" class="form-select" onchange="document.getElementById('filterForm').submit()">
                    <option value="">Semua Kategori</option>
                    @foreach($kategori as $k)
                        <option value="{{ $k->id }}" {{ (isset($selectedCategory) && $selectedCategory == $k->id) ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    <table class="table table-striped align-middle">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Kategori</th>
                <th>Deskripsi</th>
                @if(auth()->user() && auth()->user()->canManageWarehouse())
                <th>Aksi</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($kategori as $idx => $k)
            <tr>
                <td>{{ $kategori->firstItem() + $idx }}</td>
                <td>{{ $k->nama_kategori }}</td>
                <td>{{ $k->deskripsi }}</td>
                @if(auth()->user() && auth()->user()->canManageWarehouse())
                <td>
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal" 
                            data-id="{{ $k->id }}" 
                            data-nama="{{ $k->nama_kategori }}" 
                            data-deskripsi="{{ $k->deskripsi }}">Edit</button>
                    <form action="{{ route('kategori.destroy', $k->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kategori?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" type="submit">Hapus</button>
                    </form>
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $kategori->links() }}

    @if(isset($barang))
        <div class="card mt-4">
            <div class="card-header"><h5>Daftar Barang di Kategori: {{ optional($kategori->where('id', $selectedCategory)->first())->nama_kategori ?? 'Terpilih' }}</h5></div>
            <div class="card-body table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th>Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($barang as $idx => $b)
                            <tr>
                                <td>{{ $barang->firstItem() + $idx }}</td>
                                <td>{{ $b->nama_barang }}</td>
                                <td>{{ $b->kategori->nama_kategori ?? '-' }}</td>
                                <td>{{ $b->stok }}</td>
                                <td>{{ number_format($b->harga, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-muted">Belum ada barang pada kategori ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $barang->links() }}
            </div>
        </div>
    @endif

    @if(auth()->user() && auth()->user()->canManageWarehouse())
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Kategori</label>
                            <input type="text" name="nama_kategori" id="edit_nama" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <input type="text" name="deskripsi" id="edit_deskripsi" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editModal = document.getElementById('editModal');
            if (editModal) {
                editModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const id = button.getAttribute('data-id');
                    const nama = button.getAttribute('data-nama');
                    const deskripsi = button.getAttribute('data-deskripsi');
                    
                    const form = document.getElementById('editForm');
                    const namaInput = document.getElementById('edit_nama');
                    const deskripsiInput = document.getElementById('edit_deskripsi');
                    
                    form.action = '{{ route("kategori.update", ":id") }}'.replace(':id', id);
                    namaInput.value = nama;
                    deskripsiInput.value = deskripsi;
                });
            }
        });
    </script>
    @endif
@endsection

@push('styles')
<style>
    #categoryForm .card-header,
    .card-header h5 {
        font-weight: 600;
    }

    .table td, .table th {
        vertical-align: middle;
    }
</style>
@endpush
