@extends('admin.master-admin')

@section('title', 'Pengaturan Fitur')

@section('content')
<div class="page-heading">
    <h3>Pengaturan Fitur</h3>
    <p class="text-muted">On/Off fitur aplikasi (SuperAdmin)</p>
    @if(session('success'))
        <div class="alert alert-success mt-2">{{ session('success') }}</div>
    @endif
</div>

<div class="row">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header"><h5>Tambah Fitur</h5></div>
            <div class="card-body">
                <form method="POST" action="{{ route('features.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Key</label>
                        <input type="text" name="key" class="form-control" required placeholder="contoh: enable_export"/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Route Name</label>
                        <input type="text" name="route_name" class="form-control" placeholder="Kosongkan untuk auto-generate: feature.{key}" />
                        <small class="text-muted">Kosongkan untuk auto-generate route dinamis (feature.{key}). Jika diisi, gunakan route yang sudah ada di web.php</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Icon</label>
                        <input type="text" name="icon" class="form-control" placeholder="contoh: bi-basket-fill" />
                        <small class="text-muted">Class icon Bootstrap Icons (opsional)</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="enabled" id="enabled" checked>
                        <label class="form-check-label" for="enabled">Aktif (default: aktif)</label>
                    </div>
                    <button class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card">
            <div class="card-header"><h5>Daftar Fitur</h5></div>
            <div class="card-body table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Key</th>
                            <th>Nama</th>
                            <th>Route</th>
                            <th>Icon</th>
                            <th>Deskripsi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($features as $f)
                            <tr>
                                <td><code>{{ $f->key }}</code></td>
                                <td><strong>{{ $f->name }}</strong></td>
                                <td><code>{{ $f->route_name ?? '-' }}</code></td>
                                <td><code>{{ $f->icon ?? '-' }}</code></td>
                                <td>{{ $f->description ?? '-' }}</td>
                                <td>
                                    <form method="POST" action="{{ route('features.update', $f) }}" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="enabled" value="{{ $f->enabled ? 0 : 1 }}">
                                        <button type="submit" class="btn btn-sm {{ $f->enabled ? 'btn-success' : 'btn-outline-secondary' }}">
                                            {{ $f->enabled ? 'âœ“ AKTIF' : 'âœ— NONAKTIF' }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    @if(!in_array($f->key, ['data-barang', 'kategori-barang', 'laporan-barang', 'dashboard']))
                                    <form method="POST" action="{{ route('features.destroy', $f) }}" class="d-inline" onsubmit="return confirm('Hapus fitur ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
                                    @else
                                    <span class="text-muted small">Fitur Sistem</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-muted">Belum ada fitur.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection



