@extends('admin.master-admin')

@section('title', 'Manajemen Akun')

@section('content')
<div class="page-heading">
    <h3>Manajemen Akun</h3>
    @if(session('success'))
        <div class="alert alert-success mt-2">{{ session('success') }}</div>
    @endif
</div>

<div class="card">
    <div class="card-header"><h5>Daftar Pengguna</h5></div>
    <div class="card-body table-responsive">
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $u)
                <tr>
                    <td>{{ $u->name }}</td>
                    <td>{{ $u->email }}</td>
                    <td>
                        @php
                            $normalizedRole = $u->normalizedRole();
                            $badgeClass = match($normalizedRole) {
                                'superadmin' => 'bg-danger',
                                'admin' => 'bg-primary',
                                'staff_gudang' => 'bg-warning text-dark',
                                'staff_penjualan' => 'bg-info text-dark',
                                'kurir' => 'bg-success',
                                'customer' => 'bg-secondary',
                                default => 'bg-secondary',
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }}">
                            Role: {{ $u->roleLabel() }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $u->id }}">
                                Ubah Role
                            </button>
                            <form method="POST" action="{{ route('admin.users.destroy', $u) }}" class="d-inline" onsubmit="return confirm('Yakin hapus akun ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                
                <div class="modal fade" id="editModal{{ $u->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Ubah Role - {{ $u->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form method="POST" action="{{ route('admin.users.update', $u) }}">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Nama</label>
                                        <input type="text" name="name" class="form-control" value="{{ $u->name }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" value="{{ $u->email }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Role</label>
                                        <select name="role" class="form-select" required>
                                            @foreach(\App\Models\User::roleOptions() as $roleKey => $roleName)
                                                <option value="{{ $roleKey }}" @selected($u->normalizedRole()===$roleKey)>{{ $roleName }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Password Baru (opsional)</label>
                                        <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password">
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
                @endforeach
            </tbody>
        </table>
        <div class="mt-2">{{ $users->links() }}</div>
    </div>
</div>
@endsection
