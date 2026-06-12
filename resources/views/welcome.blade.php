@extends('admin.master-admin')

@section('title', 'Welcome')

@section('content')
    <div class="page-heading">
        <h3>Welcome</h3>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Selamat Datang, {{ auth()->user()->name }}!</h4>
                    <p class="card-text">Anda telah berhasil login ke sistem TheVoid Inventory.</p>
                    <p class="card-text">Role Anda: <span class="badge bg-secondary">{{ auth()->user()->roleLabel() }}</span></p>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">Ke Dashboard</a>
                </div>
            </div>
        </div>
    </div>
@endsection
