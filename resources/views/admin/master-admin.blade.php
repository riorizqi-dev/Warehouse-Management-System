<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - RakElektronik</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/iconly.css') }}">

    @stack('styles')

    <style>
        :root {
            --bg-main: #111317;
            --bg-surface: #181c22;
            --bg-elevated: #202632;
            --border-soft: #2e3747;
            --text-primary: #e8edf5;
            --text-muted: #9ca8b8;
            --accent-main: #f97316;
            --accent-soft: #fb923c;
            --accent-alt: #22c55e;
            --danger: #ef4444;
            --warning: #f59e0b;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background:
                radial-gradient(circle at 10% 0%, rgba(249, 115, 22, 0.14), transparent 28%),
                radial-gradient(circle at 88% 8%, rgba(34, 197, 94, 0.1), transparent 32%),
                var(--bg-main);
            color: var(--text-primary);
            min-height: 100vh;
            max-width: 100%;
            overflow-x: clip;
            overscroll-behavior-x: none;
        }

        #app {
            min-height: 100vh;
            display: flex;
            max-width: 100%;
            overflow-x: clip;
        }

        #main {
            width: calc(100% - 300px);
            min-height: 100vh;
            margin-left: 300px;
            transition: margin-left 0.25s ease;
            padding: 1.5rem;
            overflow-x: clip !important;
            overflow-y: visible !important;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.25rem;
            padding: 0.9rem 1.1rem;
            border: 1px solid var(--border-soft);
            border-radius: 12px;
            background: rgba(24, 28, 34, 0.94);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.28);
            position: relative;
            z-index: 1400;
            overflow: visible;
        }

        .topbar-left,
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .search-group {
            position: relative;
            min-width: 260px;
            display: flex;
            align-items: center;
        }

        .search-group input {
            width: 100%;
            border: 1px solid var(--border-soft);
            background: rgba(32, 38, 50, 0.8);
            color: var(--text-primary);
            border-radius: 10px;
            padding: 0.65rem 0.8rem 0.65rem 2.5rem;
            min-height: 42px;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .search-group input:focus {
            border-color: rgba(249, 115, 22, 0.75);
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.2);
        }

        .search-group .search-icon {
            position: absolute;
            left: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            width: 16px;
            height: 16px;
            pointer-events: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            z-index: 1;
        }

        .search-group .search-icon svg {
            width: 16px;
            height: 16px;
        }

        .topbar-btn,
        .profile-btn {
            border: 1px solid var(--border-soft);
            background: rgba(32, 38, 50, 0.8);
            color: var(--text-primary);
            border-radius: 10px;
            min-width: 42px;
            min-height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.03);
        }

        .topbar-btn:hover,
        .profile-btn:hover {
            border-color: rgba(249, 115, 22, 0.75);
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.18);
            color: #f8fafc;
        }

        .notification-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: var(--danger);
            position: absolute;
            right: 10px;
            top: 8px;
            border: 2px solid var(--bg-elevated);
        }

        .notification-count {
            position: absolute;
            right: -6px;
            top: -7px;
            min-width: 18px;
            height: 18px;
            border-radius: 999px;
            background: #ef4444;
            color: #fff;
            font-size: 0.65rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 5px;
            font-weight: 700;
            border: 2px solid var(--bg-surface);
        }

        .page-content {
            animation: fadeUp 0.45s ease both;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card {
            background: rgba(24, 28, 34, 0.95);
            border: 1px solid var(--border-soft);
            border-radius: 12px;
            box-shadow: 0 8px 22px rgba(0, 0, 0, 0.24);
            color: var(--text-primary);
        }

        .card-header {
            border-bottom: 1px solid var(--border-soft);
            background: transparent;
        }

        .table {
            color: var(--text-primary);
            --bs-table-bg: transparent;
            --bs-table-border-color: rgba(156, 168, 184, 0.2);
            --bs-table-striped-bg: rgba(39, 45, 58, 0.45);
            --bs-table-striped-color: var(--text-primary);
            --bs-table-hover-bg: rgba(249, 115, 22, 0.08);
            --bs-table-hover-color: var(--text-primary);
        }

        .table th {
            color: #cbd5e1;
            font-weight: 600;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent-main), var(--accent-soft));
            border: none;
            box-shadow: 0 8px 20px rgba(249, 115, 22, 0.26);
        }

        .btn-primary:hover {
            box-shadow: 0 10px 24px rgba(249, 115, 22, 0.36);
            transform: translateY(-1px);
        }

        .btn-secondary,
        .btn-success,
        .btn-warning,
        .btn-danger {
            border: none;
        }

        .form-control,
        .form-select {
            border: 1px solid var(--border-soft);
            background: rgba(20, 24, 33, 0.88);
            color: var(--text-primary);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: rgba(249, 115, 22, 0.75);
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.18);
            background: rgba(20, 24, 33, 0.95);
            color: var(--text-primary);
        }

        .modal-content {
            border: 1px solid var(--border-soft);
            background: linear-gradient(180deg, rgba(17, 24, 39, 0.98), rgba(15, 23, 42, 0.95));
            color: var(--text-primary);
        }

        .dropdown-menu {
            border: 1px solid rgba(148, 163, 184, 0.24);
            background: rgba(15, 23, 42, 0.96);
            color: var(--text-primary);
            border-radius: 12px;
            overflow: hidden;
            z-index: 2500;
            margin-top: 10px;
        }

        .dropdown-item {
            color: var(--text-primary);
        }

        .dropdown-item:hover {
            background: rgba(249, 115, 22, 0.12);
            color: #f8fafc;
        }

        .topbar-right .dropdown {
            position: relative;
        }

        .profile-btn {
            min-height: 44px;
            border-color: rgba(148, 163, 184, 0.3);
            padding-right: 0.65rem;
        }

        .profile-btn::after {
            margin-left: 0.55rem;
        }

        .notification-menu {
            width: 320px;
            max-width: calc(100vw - 24px);
            padding: 0;
        }

        .notification-item {
            padding: 0.75rem 0.95rem;
            border-bottom: 1px solid rgba(148, 163, 184, 0.15);
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-item small {
            color: var(--text-muted);
        }

        .notification-actions {
            padding: 0.55rem 0.8rem;
            border-top: 1px solid rgba(148, 163, 184, 0.15);
            background: rgba(20, 24, 33, 0.7);
        }

        .footer-wrap {
            margin-top: 1.2rem;
            color: var(--text-muted);
            font-size: 0.9rem;
            display: flex;
            justify-content: space-between;
            gap: 0.6rem;
        }

        .mono {
            font-family: 'JetBrains Mono', monospace;
            letter-spacing: 0.2px;
        }

        .page-heading h1,
        .page-heading h2,
        .page-heading h3,
        .page-heading h4,
        .card-header h5,
        h1, h2, h3, h4, h5 {
            font-family: 'Poppins', sans-serif;
            letter-spacing: 0.2px;
        }

        @media (max-width: 1199px) {
            #main {
                width: 100%;
                margin-left: 0;
            }

            body.sidebar-open #main {
                overflow: hidden;
            }

            .search-group {
                min-width: 0;
                width: 100%;
            }

            .topbar {
                flex-wrap: wrap;
            }

            .topbar-right {
                margin-left: auto;
            }
        }
    </style>
</head>

<body>
<div id="app">
    @include('admin.sidebar-admin')

    @php
        $topNotifications = collect();
        if (\Illuminate\Support\Facades\Schema::hasTable('notifications')) {
            $topNotifications = \App\Models\UserNotification::where('user_id', auth()->id())
                ->latest()
                ->limit(8)
                ->get()
                ->map(function ($note) {
                    return [
                        'title' => $note->title,
                        'message' => $note->message,
                        'time' => optional($note->created_at)->diffForHumans(),
                    ];
                });
        }

        if ($topNotifications->isEmpty()) {
            $topNotifications = \App\Models\InventoryLog::with(['dataBarang', 'user'])
                ->latest()
                ->limit(5)
                ->get()
                ->map(function ($note) {
                    return [
                        'title' => ucfirst((string) $note->action),
                        'message' => optional($note->dataBarang)->nama_barang ?? 'Barang dihapus',
                        'time' => optional($note->user)->name . ' - ' . optional($note->created_at)->diffForHumans(),
                    ];
                });
        }
    @endphp

    <div id="main">
        <header class="topbar">
            <div class="topbar-left">
                <button class="topbar-btn d-xl-none" type="button" id="sidebarToggle" aria-label="Toggle Sidebar">
                    <i data-lucide="menu"></i>
                </button>
                <form class="search-group" method="GET" action="{{ url()->current() }}">
                    @foreach(request()->except(['q', 'page']) as $paramKey => $paramValue)
                        @if(is_array($paramValue))
                            @foreach($paramValue as $arrayValue)
                                <input type="hidden" name="{{ $paramKey }}[]" value="{{ $arrayValue }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $paramKey }}" value="{{ $paramValue }}">
                        @endif
                    @endforeach
                    <span class="search-icon"><i data-lucide="search"></i></span>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari di halaman ini... (Enter)">
                </form>
            </div>

            <div class="topbar-right">
                <div class="dropdown">
                    <button class="topbar-btn position-relative dropdown-toggle" id="notificationDropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" aria-label="Notifications">
                        <i data-lucide="bell" style="width:16px;height:16px"></i>
                        @if($topNotifications->isNotEmpty())
                            <span class="notification-dot" id="notificationDot"></span>
                            <span class="notification-count" id="notificationCount">{{ min($topNotifications->count(), 9) }}</span>
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-end notification-menu" aria-labelledby="notificationDropdown">
                        <div class="px-3 py-2 border-bottom" style="border-color:rgba(148,163,184,0.2)!important;">
                            <div class="fw-semibold">Notifikasi</div>
                            <small class="text-secondary">Update terbaru sistem</small>
                        </div>
                        @forelse($topNotifications as $note)
                            <div class="notification-item">
                                <div class="fw-semibold text-capitalize">{{ $note['title'] ?? '-' }}</div>
                                <small>{{ $note['message'] ?? '-' }}</small><br>
                                <small>{{ $note['time'] ?? '-' }}</small>
                            </div>
                        @empty
                            <div class="notification-item">
                                <small>Tidak ada notifikasi baru.</small>
                            </div>
                        @endforelse
                        <div class="notification-actions d-flex justify-content-between align-items-center">
                            <small class="text-secondary">Total {{ $topNotifications->count() }} notifikasi</small>
                            <button type="button" class="btn btn-sm btn-primary py-1 px-2" id="markNotifRead">Tandai dibaca</button>
                        </div>
                    </div>
                </div>

                <div class="dropdown">
                    <button class="profile-btn dropdown-toggle px-2" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="gap:0.55rem;">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width:30px;height:30px;background:linear-gradient(135deg,#f97316,#fb923c);color:#1f2937;font-weight:700;">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </span>
                        <span class="d-none d-md-inline text-start" style="line-height:1.1;">
                            <span class="d-block" style="font-size:0.84rem;font-weight:600;">{{ auth()->user()->name ?? 'User' }}</span>
                            <span class="d-block mono" style="font-size:0.67rem;color:var(--text-muted);">{{ auth()->user()->roleLabel() }}</span>
                        </span>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown" style="min-width:240px;">
                        <li class="px-3 py-2">
                            <div class="fw-semibold">{{ auth()->user()->name ?? '-' }}</div>
                            <small class="text-secondary">{{ auth()->user()->email ?? '-' }}</small>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i data-lucide="user" style="width:15px;height:15px;margin-right:8px;"></i>Profil</a></li>
                        <li><a class="dropdown-item" href="{{ route('pengaturan') }}"><i data-lucide="settings" style="width:15px;height:15px;margin-right:8px;"></i>Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit" class="dropdown-item"><i data-lucide="log-out" style="width:15px;height:15px;margin-right:8px;"></i>Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <div class="page-content">
            @yield('content')
        </div>

        <footer class="footer-wrap">
            <span>2026 &copy; RakElektronik</span>
            <span class="mono">Built with Laravel + Bootstrap</span>
        </footer>
    </div>
</div>

<script src="{{ asset('assets/static/js/components/dark.js') }}"></script>
<script src="{{ asset('assets/compiled/js/app.js') }}"></script>
<script src="https://unpkg.com/lucide@latest"></script>

<script>
    window.laravelRoutes = {
        @php
            try {
                $features = \App\Models\FeatureToggle::whereNotNull('route_name')
                    ->where('route_name', '!=', '')
                    ->get(['route_name']);
                foreach ($features as $feat) {
                    try {
                        $routeUrl = route($feat->route_name);
                        echo "'{$feat->route_name}': '{$routeUrl}',\n";
                    } catch (\Exception $e) {
                    }
                }
            } catch (\Exception $e) {
            }
        @endphp
    };

    (function () {
        const sidebarToggle = document.getElementById('sidebarToggle');
        const notificationDot = document.getElementById('notificationDot');
        const notificationCount = document.getElementById('notificationCount');
        const markNotifRead = document.getElementById('markNotifRead');
        const notifStorageKey = 'darkstock_notifications_read_{{ auth()->id() }}';

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function () {
                document.body.classList.toggle('sidebar-open');
                const sidebar = document.getElementById('sidebar');
                if (sidebar) {
                    sidebar.classList.toggle('is-open');
                }
            });
        }

        if (localStorage.getItem(notifStorageKey) === '1') {
            if (notificationDot) notificationDot.style.display = 'none';
            if (notificationCount) notificationCount.style.display = 'none';
        }

        if (markNotifRead) {
            markNotifRead.addEventListener('click', function () {
                localStorage.setItem(notifStorageKey, '1');
                if (notificationDot) notificationDot.style.display = 'none';
                if (notificationCount) notificationCount.style.display = 'none';
            });
        }

        if (window.lucide) {
            window.lucide.createIcons();
        }

        document.body.classList.remove('modal-open');
        document.body.style.removeProperty('overflow');
        document.body.style.removeProperty('padding-right');
        document.querySelectorAll('.modal-backdrop').forEach((el) => el.remove());
    })();
</script>
<script src="{{ asset('assets/static/js/feature-toggle-realtime.js') }}"></script>
@stack('scripts')
</body>
</html>
