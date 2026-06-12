<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Customer Portal') - RakElektronik</title>
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <style>
        :root {
            --bg-main: #0f1319;
            --bg-surface: #171d26;
            --bg-elev: #1f2733;
            --text-main: #e5edf7;
            --text-muted: #9db0c8;
            --border: #2f3b4c;
            --accent: #f97316;
        }

        body {
            background:
                radial-gradient(circle at 8% 0%, rgba(249, 115, 22, 0.12), transparent 28%),
                radial-gradient(circle at 92% 6%, rgba(56, 189, 248, 0.08), transparent 30%),
                var(--bg-main);
            color: var(--text-main);
        }
        .portal-shell { min-height: 100vh; padding-bottom: 72px; }
        .portal-nav { background: rgba(23, 29, 38, 0.94); border-bottom: 1px solid var(--border); }
        .portal-nav a { color: var(--text-main); }
        .brand { font-weight: 700; color: var(--accent) !important; text-decoration: none; }
        .card {
            border: 1px solid var(--border);
            border-radius: 14px;
            background: rgba(23, 29, 38, 0.94);
            color: var(--text-main);
        }
        .text-muted { color: var(--text-muted) !important; }

        .table {
            --bs-table-bg: transparent;
            --bs-table-color: var(--text-main);
            --bs-table-border-color: rgba(157, 176, 200, 0.2);
        }

        .form-control,
        .form-select {
            background: rgba(15, 19, 25, 0.9);
            border: 1px solid var(--border);
            color: var(--text-main);
        }

        .form-control:focus,
        .form-select:focus {
            background: rgba(15, 19, 25, 0.98);
            border-color: rgba(249, 115, 22, 0.65);
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.16);
            color: var(--text-main);
        }

        .btn-outline-secondary,
        .btn-outline-primary,
        .btn-outline-danger {
            border-color: var(--border);
            color: var(--text-main);
        }

        .btn-outline-secondary:hover,
        .btn-outline-primary:hover,
        .btn-outline-danger:hover {
            background: rgba(249, 115, 22, 0.15);
            border-color: rgba(249, 115, 22, 0.55);
            color: #fff;
        }

        .bottom-nav {
            position: fixed; left: 0; right: 0; bottom: 0; z-index: 1000;
            display: grid; grid-template-columns: repeat(5, 1fr);
            background: rgba(23, 29, 38, 0.98); border-top: 1px solid var(--border);
        }
        .bottom-nav a,
        .bottom-nav button {
            text-align: center; padding: 10px 4px; font-size: 12px; text-decoration: none; color: var(--text-muted);
        }
        .bottom-nav a.active { color: var(--accent); font-weight: 600; }

        .alert-success {
            background: rgba(16, 185, 129, 0.2);
            border-color: rgba(16, 185, 129, 0.4);
            color: #bbf7d0;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.2);
            border-color: rgba(239, 68, 68, 0.4);
            color: #fecaca;
        }

        @media (min-width: 992px) { .bottom-nav { display: none; } .portal-shell { padding-bottom: 0; } }
    </style>
</head>
<body>
<div class="portal-shell">
    @php
        $unreadNotificationCount = 0;
        if (auth()->check() && \Illuminate\Support\Facades\Schema::hasTable('notifications')) {
            $unreadNotificationCount = auth()->user()->unreadNotifications()->count();
        }
    @endphp
    <nav class="portal-nav py-2 mb-3">
        <div class="container d-flex justify-content-between align-items-center gap-2">
            <a href="{{ route('customer.dashboard') }}" class="brand">RakElektronik Portal</a>
            <div class="d-none d-lg-flex gap-3 align-items-center">
                <a href="{{ route('customer.catalog') }}" class="text-decoration-none">Katalog</a>
                <a href="{{ route('customer.cart') }}" class="text-decoration-none">Keranjang</a>
                <a href="{{ route('customer.orders') }}" class="text-decoration-none">
                    Pesanan Saya
                    @if($unreadNotificationCount > 0)
                        <span class="badge text-bg-warning">{{ $unreadNotificationCount }}</span>
                    @endif
                </a>
                <form method="POST" action="{{ route('logout') }}" class="m-0">@csrf<button class="btn btn-sm btn-outline-secondary">Logout</button></form>
            </div>
        </div>
    </nav>

    <main class="container pb-4">
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
        @yield('content')
    </main>
</div>

@include('admin.partials.chatbot-widget')
<style>
    @media (max-width: 991px) {
        .cs-chatbot {
            bottom: 5.2rem;
        }
    }
</style>

<div class="bottom-nav">
    <a href="{{ route('customer.dashboard') }}" class="{{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">Home</a>
    <a href="{{ route('customer.catalog') }}" class="{{ request()->routeIs('customer.catalog*') ? 'active' : '' }}">Katalog</a>
    <a href="{{ route('customer.cart') }}" class="{{ request()->routeIs('customer.cart') ? 'active' : '' }}">Keranjang</a>
    <a href="{{ route('customer.orders') }}" class="{{ request()->routeIs('customer.orders*') ? 'active' : '' }}">
        Pesanan
        @if($unreadNotificationCount > 0)
            ({{ $unreadNotificationCount }})
        @endif
    </a>
    <form method="POST" action="{{ route('logout') }}" class="m-0 d-flex align-items-center justify-content-center">@csrf<button class="border-0 bg-transparent p-0">Logout</button></form>
</div>
</body>
</html>
