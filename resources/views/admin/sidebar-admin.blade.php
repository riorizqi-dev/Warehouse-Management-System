<div id="sidebar">
    <div class="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('dashboard') }}" class="brand-link text-decoration-none">
                <span class="brand-logo" aria-hidden="true">
                    <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="logoGrad" x1="10" y1="8" x2="56" y2="56" gradientUnits="userSpaceOnUse">
                                <stop offset="0" stop-color="#f97316"/>
                                <stop offset="1" stop-color="#22c55e"/>
                            </linearGradient>
                        </defs>
                        <path d="M32 6 54 18v28L32 58 10 46V18L32 6Z" stroke="url(#logoGrad)" stroke-width="3"/>
                        <path d="M32 6v52M10 18l22 12 22-12" stroke="url(#logoGrad)" stroke-width="3"/>
                        <path d="M20 32v10m12-6v10m12-14v10" stroke="url(#logoGrad)" stroke-width="3" stroke-linecap="round"/>
                    </svg>
                </span>
                <span>
                    <strong>RakElektronik</strong>
                    <small>Sistem Gudang Elektronik</small>
                </span>
            </a>
        </div>

        @php
            $authUser = auth()->user();
            $canMasterData = user_can_menu('master_data') || $authUser->canManageMasterData();
            $canSales = user_can_menu('orders') || $authUser->canManageSales();
            $canWarehouse = user_can_menu('warehouse_ops') || $authUser->canManageWarehouse();
            $canShipping = user_can_menu('shipping') || $authUser->canManageShipping();
            $canReports = user_can_menu('reports');
            $isBarangRoute = request()->routeIs('barang.*');
            $menuState = request('menu');
            $isBarangActive = $isBarangRoute && $menuState !== 'stok';
            $isStokActive = (request()->routeIs('stok.*')) || ($isBarangRoute && $menuState === 'stok');
            $logType = request('type');
            $isBarangMasukActive = request()->routeIs('inventory.logs') && in_array($logType, ['masuk', 'in'], true);
            $isBarangKeluarActive = request()->routeIs('inventory.logs') && in_array($logType, ['keluar', 'out'], true);
            $isOrdersActive = request()->routeIs('orders.*');
            $isCustomersActive = request()->routeIs('customers.*');
            $isPengirimanActive = request()->routeIs('orders.*') && in_array(request('status'), ['packed', 'shipped', 'completed'], true);
        @endphp

        <div class="sidebar-menu">
            <p class="menu-label">Overview</p>
            <ul class="menu-list">
                <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}">
                        <i data-lucide="layout-dashboard"></i><span>Dashboard</span>
                    </a>
                </li>
            </ul>

            <p class="menu-label mt-3">Master Data</p>
            <ul class="menu-list">
                @if($canWarehouse && route_exists('barang.index'))
                <li class="menu-item {{ $isBarangActive ? 'active' : '' }}">
                    <a href="{{ route('barang.index') }}">
                        <i data-lucide="package-search"></i><span>Data Barang</span>
                    </a>
                </li>
                @endif

                @if($canMasterData && route_exists('kategori.barang'))
                <li class="menu-item {{ request()->routeIs('kategori.*') || request()->routeIs('kategori.barang') ? 'active' : '' }}">
                    <a href="{{ route('kategori.barang') }}">
                        <i data-lucide="tag"></i><span>Kategori</span>
                    </a>
                </li>
                @endif

                @if($canSales)
                <li class="menu-item {{ $isCustomersActive ? 'active' : '' }}">
                    <a href="{{ route_exists('customers.index') ? route('customers.index') : '#' }}">
                        <i data-lucide="users"></i><span>Data Customer</span>
                    </a>
                </li>
                @endif
            </ul>

            @if($canSales || $canWarehouse || $canShipping)
            <p class="menu-label mt-3">Sales & Order</p>
            <ul class="menu-list">
                <li class="menu-item {{ $isOrdersActive ? 'active' : '' }}">
                    <a href="{{ route_exists('orders.index') ? route('orders.index') : '#' }}">
                        <i data-lucide="clipboard-list"></i><span>Pesanan</span>
                    </a>
                </li>
            </ul>
            @endif

            @if($canWarehouse)
            <p class="menu-label mt-3">Warehouse Operations</p>
            <ul class="menu-list">
                <li class="menu-item menu-item-soft {{ $isBarangMasukActive ? 'active' : '' }}">
                    <a href="{{ route_exists('inventory.logs') ? route('inventory.logs', ['type' => 'masuk']) : '#' }}">
                        <i data-lucide="arrow-down-to-line"></i><span>Barang Masuk</span>
                    </a>
                </li>

                <li class="menu-item menu-item-soft {{ $isBarangKeluarActive ? 'active' : '' }}">
                    <a href="{{ route_exists('inventory.logs') ? route('inventory.logs', ['type' => 'keluar']) : '#' }}">
                        <i data-lucide="truck"></i><span>Pengeluaran Barang</span>
                    </a>
                </li>

                <li class="menu-item {{ $isStokActive ? 'active' : '' }}">
                    <a href="{{ route_exists('barang.index') ? route('barang.index', ['menu' => 'stok']) : '#' }}">
                        <i data-lucide="boxes"></i><span>Stok</span>
                    </a>
                </li>
            </ul>
            @endif

            @if($canShipping)
            <p class="menu-label mt-3">Shipping</p>
            <ul class="menu-list">
                <li class="menu-item {{ $isPengirimanActive ? 'active' : '' }}">
                    <a href="{{ route_exists('orders.index') ? route('orders.index', ['status' => 'packed']) : '#' }}">
                        <i data-lucide="send"></i><span>Pengiriman</span>
                    </a>
                </li>
            </ul>
            @endif

            @if($canReports)
            <p class="menu-label mt-3">Reports</p>
            <ul class="menu-list">
                @if(route_exists('laporan.barang'))
                <li class="menu-item {{ request()->routeIs('laporan.*') || request()->routeIs('laporan.barang') ? 'active' : '' }}">
                    <a href="{{ route('laporan.barang') }}">
                        <i data-lucide="file-bar-chart-2"></i><span>Laporan</span>
                    </a>
                </li>
                @endif
            </ul>
            @endif

            <p class="menu-label mt-3">Administration</p>
            <ul class="menu-list">
                @if(auth()->user()->isSuperAdmin() && route_exists('admin.users.index'))
                <li class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.users.index') }}">
                        <i data-lucide="users-round"></i><span>Users / Admin</span>
                    </a>
                </li>
                @endif

                @if((auth()->user()->isAdmin() || auth()->user()->isSuperAdmin() || auth()->user()->isSalesStaff()) && route_exists('admin.faqs.index'))
                <li class="menu-item {{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.faqs.index') }}">
                        <i data-lucide="message-square"></i><span>FAQ Chatbot</span>
                    </a>
                </li>
                @endif

                <li class="menu-item {{ request()->routeIs('pengaturan') ? 'active' : '' }}">
                    <a href="{{ route('pengaturan') }}">
                        <i data-lucide="settings-2"></i><span>Settings</span>
                    </a>
                </li>
            </ul>

            @php
                $dynamicFeatures = get_enabled_features_for_menu();
            @endphp
            @if($dynamicFeatures->isNotEmpty())
                <p class="menu-label mt-4">Dynamic Modules</p>
                <ul class="menu-list">
                    @foreach($dynamicFeatures as $feature)
                        @php
                            $href = (strpos($feature->route_name, 'feature.') === 0)
                                ? route('feature.show', ['key' => $feature->key])
                                : (route_exists($feature->route_name) ? route($feature->route_name) : '#');
                        @endphp
                        <li class="menu-item {{ is_route_active($feature->route_name) ? 'active' : '' }}">
                            <a href="{{ $href }}">
                                <i data-lucide="component"></i><span>{{ $feature->name }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>

<style>
    #sidebar {
        width: 300px;
        min-width: 300px;
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        z-index: 1200;
        border-right: 1px solid #2e3747;
        background: linear-gradient(180deg, #171b22, #111317);
        box-shadow: 14px 0 36px rgba(0, 0, 0, 0.34);
        overflow: hidden;
    }

    .sidebar-wrapper {
        height: 100%;
        display: flex;
        flex-direction: column;
        padding: 1.2rem 1rem 1.1rem;
        min-height: 0;
    }

    .sidebar-brand {
        padding: 0.2rem 0.35rem 1rem;
        border-bottom: 1px solid rgba(156, 168, 184, 0.2);
        margin-bottom: 0.9rem;
    }

    .brand-link {
        display: flex;
        align-items: center;
        gap: 0.8rem;
        color: #e8edf5;
    }

    .brand-logo {
        width: 44px;
        height: 44px;
        display: inline-flex;
        border-radius: 10px;
        background: rgba(32, 38, 50, 0.88);
        border: 1px solid rgba(249, 115, 22, 0.42);
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
        align-items: center;
        justify-content: center;
        padding: 7px;
    }

    .brand-link strong {
        display: block;
        letter-spacing: 0.4px;
    }

    .brand-link small {
        color: #9ca8b8;
        font-size: 0.72rem;
    }

    .sidebar-menu {
        flex: 1 1 auto;
        min-height: 0;
        overflow-y: auto;
        overflow-x: hidden;
        padding-right: 0.2rem;
        overscroll-behavior: contain;
        -webkit-overflow-scrolling: touch;
        scroll-behavior: smooth;
        scrollbar-width: thin;
        scrollbar-color: rgba(156, 168, 184, 0.35) transparent;
    }

    .sidebar-menu::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    .sidebar-menu::-webkit-scrollbar-thumb {
        background: rgba(156, 168, 184, 0.3);
        border-radius: 999px;
    }

    .sidebar-menu::-webkit-scrollbar-thumb:hover {
        background: rgba(156, 168, 184, 0.5);
    }

    .sidebar-menu::-webkit-scrollbar-track {
        background: transparent;
    }

    .sidebar-menu .ps__rail-y,
    .sidebar-menu .ps__rail-x {
        display: none !important;
        opacity: 0 !important;
    }

    .menu-label {
        color: #7d8aa0;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        font-size: 0.7rem;
        margin: 0.35rem 0 0.7rem;
        padding-left: 0.55rem;
    }

    .menu-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: grid;
        gap: 0.32rem;
    }

    .menu-item a {
        display: flex;
        align-items: center;
        gap: 0.62rem;
        color: #c7d0df;
        text-decoration: none;
        border: 1px solid #1f2430;
        border-radius: 10px;
        padding: 0.62rem 0.72rem;
        transition: all 0.2s ease;
        font-size: 0.92rem;
        font-weight: 500;
        position: relative;
    }

    .menu-item a i {
        width: 17px;
        height: 17px;
        color: #9ca8b8;
    }

    .menu-item a:hover {
        background: rgba(249, 115, 22, 0.1);
        border-color: rgba(249, 115, 22, 0.35);
        color: #f8fafc;
        transform: translateX(2px);
    }

    .menu-item.active a {
        color: #fff;
        background: rgba(249, 115, 22, 0.14);
        border-color: rgba(249, 115, 22, 0.5);
        box-shadow: inset 3px 0 0 #f97316;
    }

    .menu-item.active a i {
        color: #fb923c;
    }

    @media (max-width: 1199px) {
        #sidebar {
            transform: translateX(-102%);
            transition: transform 0.25s ease;
        }

        #sidebar.is-open {
            transform: translateX(0);
        }
    }
</style>
