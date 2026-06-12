@extends('admin.master-admin')

@section('title', 'Manajemen FAQ')

@section('content')
<div class="page-heading">
    <h3>Manajemen FAQ Chatbot</h3>
    <p class="text-muted">Kelola jawaban FAQ yang dipakai chatbot customer service.</p>
    @if(session('success'))
        <div class="alert alert-success mt-2">{{ session('success') }}</div>
    @endif
</div>

<div class="row">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header"><h5>Tambah FAQ</h5></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.faqs.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Pertanyaan</label>
                        <input type="text" name="question" class="form-control" required placeholder="Contoh: Cara pesan barang bagaimana?">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jawaban</label>
                        <textarea name="answer" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keywords (pisahkan koma)</label>
                        <input type="text" name="keywords" class="form-control" placeholder="cara pesan,pesan barang,order">
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Urutan</label>
                            <input type="number" name="sort_order" class="form-control" value="0" min="0">
                        </div>
                        <div class="col-6 mb-3 d-flex align-items-end">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="faq_active" checked>
                                <label class="form-check-label" for="faq_active">Aktif</label>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan FAQ</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card">
            <div class="card-header"><h5>Daftar FAQ</h5></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead>
                            <tr>
                                <th>Pertanyaan</th>
                                <th>Keywords</th>
                                <th>Status</th>
                                <th width="220">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($faqs as $faq)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $faq->question }}</div>
                                        <small class="text-muted">{{ \Illuminate\Support\Str::limit($faq->answer, 90) }}</small>
                                    </td>
                                    <td><small>{{ $faq->keywords ?: '-' }}</small></td>
                                    <td>
                                        <span class="badge {{ $faq->is_active ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $faq->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td>
                                        <details>
                                            <summary class="btn btn-sm btn-outline-warning">Edit</summary>
                                            <form method="POST" action="{{ route('admin.faqs.update', $faq) }}" class="mt-2">
                                                @csrf
                                                @method('PUT')
                                                <input type="text" name="question" class="form-control form-control-sm mb-2" value="{{ $faq->question }}" required>
                                                <textarea name="answer" class="form-control form-control-sm mb-2" rows="3" required>{{ $faq->answer }}</textarea>
                                                <input type="text" name="keywords" class="form-control form-control-sm mb-2" value="{{ $faq->keywords }}">
                                                <div class="d-flex gap-2 mb-2">
                                                    <input type="number" name="sort_order" class="form-control form-control-sm" value="{{ $faq->sort_order }}" min="0">
                                                    <select name="is_active" class="form-select form-select-sm">
                                                        <option value="1" @selected($faq->is_active)>Aktif</option>
                                                        <option value="0" @selected(!$faq->is_active)>Nonaktif</option>
                                                    </select>
                                                </div>
                                                <button type="submit" class="btn btn-sm btn-warning">Simpan</button>
                                            </form>
                                        </details>
                                        <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}" class="mt-2" onsubmit="return confirm('Hapus FAQ ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-muted">Belum ada FAQ.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($faqs->hasPages())
                    <div class="mt-3">{{ $faqs->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

