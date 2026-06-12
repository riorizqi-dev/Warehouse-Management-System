@extends('admin.master-admin')

@section('title', 'Data Customer')

@section('content')
<div class="page-heading mb-3">
    <h3>Data Customer</h3>
    <p class="text-muted mb-0">Kelola pelanggan untuk pesanan barang elektronik.</p>
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

<div class="card mb-3">
    <div class="card-header"><h5 class="mb-0">Tambah Customer</h5></div>
    <div class="card-body">
        <form method="POST" action="{{ route('customers.store') }}" class="row g-2">
            @csrf
            <div class="col-md-4">
                <input type="text" name="nama_customer" class="form-control" placeholder="Nama Customer" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="kontak_person" class="form-control" placeholder="Kontak Person">
            </div>
            <div class="col-md-2">
                <input type="text" name="no_telp" class="form-control" placeholder="No Telepon">
            </div>
            <div class="col-md-3">
                <input type="email" name="email" class="form-control" placeholder="Email">
            </div>
            <div class="col-md-9">
                <input type="text" name="alamat" class="form-control" placeholder="Alamat">
            </div>
            <div class="col-md-2">
                <input type="text" name="kota" class="form-control" placeholder="Kota">
            </div>
            <div class="col-md-1 d-grid">
                <button class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><h5 class="mb-0">Daftar Customer</h5></div>
    <div class="card-body table-responsive">
        <table class="table table-sm align-middle mb-0">
            <thead>
            <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th>Kontak</th>
                <th>No Telp</th>
                <th>Kota</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            @forelse($customers as $customer)
                <tr>
                    <td>{{ $customer->kode_customer }}</td>
                    <td class="fw-semibold">{{ $customer->nama_customer }}</td>
                    <td>{{ $customer->kontak_person ?? '-' }}</td>
                    <td>{{ $customer->no_telp ?? '-' }}</td>
                    <td>{{ $customer->kota ?? '-' }}</td>
                    <td><span class="badge {{ $customer->status === 'aktif' ? 'text-bg-success' : 'text-bg-secondary' }}">{{ ucfirst($customer->status) }}</span></td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-muted">Belum ada customer.</td></tr>
            @endforelse
            </tbody>
        </table>
        <div class="mt-2">{{ $customers->links() }}</div>
    </div>
</div>
@endsection
