@extends('admin.master-admin')

@section('title', $feature->name)

@section('content')
<div class="page-heading">
    <h3>{{ $feature->name }}</h3>
    @if($feature->description)
        <p class="text-muted">{{ $feature->description }}</p>
    @endif
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>{{ $feature->name }}</h5>
            </div>
            <div class="card-body">
                <div class="text-center py-5">
                    <i class="bi {{ $feature->icon ?? 'bi-circle' }}" style="font-size: 4rem; color: #6c757d;"></i>
                    <h4 class="mt-3">{{ $feature->name }}</h4>
                    @if($feature->description)
                        <p class="text-muted">{{ $feature->description }}</p>
                    @endif
                    <p class="text-muted mt-3">
                        <small>Key: <code>{{ $feature->key }}</code></small>
                    </p>
                    <div class="alert alert-info mt-4">
                        <i class="bi bi-info-circle me-2"></i>
                        Halaman fitur ini siap digunakan. Anda dapat mengembangkan konten fitur ini sesuai kebutuhan.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

