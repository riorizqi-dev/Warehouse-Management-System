<?php

if (! function_exists('feature_enabled')) {
    function feature_enabled(string $key): bool
    {
        return \App\Helpers\FeatureHelper::isEnabled($key);
    }
}

if (! function_exists('get_enabled_features_for_menu')) {
    function get_enabled_features_for_menu(): \Illuminate\Support\Collection
    {
        try {
            $staticRouteNames = [
                'dashboard',
                'barang.index',
                'kategori.barang',
                'customers.index',
                'orders.index',
                'inventory.logs',
                'laporan.barang',
                'admin.users.index',
                'admin.faqs.index',
                'pengaturan',
            ];

            $staticKeys = [
                'dashboard',
                'data-barang',
                'kategori-barang',
                'laporan-barang',
            ];

            return \App\Models\FeatureToggle::where('enabled', true)
                ->whereNotNull('route_name')
                ->where('route_name', '!=', '')
                ->whereNotIn('key', $staticKeys)
                ->whereNotIn('route_name', $staticRouteNames)
                ->orderBy('name')
                ->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }
}

if (! function_exists('is_route_active')) {
    function is_route_active(string $routeName): bool
    {
        if (empty($routeName)) {
            return false;
        }

        if (strpos($routeName, 'feature.') === 0) {
            $key = str_replace('feature.', '', $routeName);

            return request()->routeIs('feature.show') && request()->route('key') === $key;
        }

        try {
            route($routeName);

            $current = optional(request()->route())->getName();
            if (empty($current)) {
                return false;
            }

            if (strpos($routeName, 'feature.') === 0) {
                $key = str_replace('feature.', '', $routeName);

                return request()->routeIs('feature.show') && request()->route('key') === $key;
            }

            if ($current === $routeName) {
                return true;
            }

            if (str_starts_with($current, $routeName.'.')) {
                return true;
            }

            return false;
        } catch (\Exception $e) {
            return false;
        }
    }
}

if (! function_exists('route_exists')) {
    function route_exists(string $routeName): bool
    {
        if (empty($routeName)) {
            return false;
        }

        if (strpos($routeName, 'feature.') === 0) {
            return true;
        }

        try {
            route($routeName);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}

if (! function_exists('order_status_label')) {
    function order_status_label(?string $status): string
    {
        return match ((string) $status) {
            'pending' => 'Pending Verifikasi',
            'waiting_restock' => 'Menunggu Restock',
            'reserved' => 'Stok Ter-reservasi',
            'picking' => 'Picking Gudang',
            'packed' => 'Siap Kirim',
            'shipped' => 'Dikirim',
            'completed' => 'Selesai',
            'rejected' => 'Ditolak',
            default => strtoupper((string) $status),
        };
    }
}

if (! function_exists('role_label')) {
    function role_label(?string $role): string
    {
        $labels = \App\Models\User::roleOptions();
        $user = new \App\Models\User;
        $user->role = $role;
        $normalized = $user->normalizedRole();

        return $labels[$normalized] ?? ucfirst(str_replace('_', ' ', (string) $role));
    }
}

if (! function_exists('role_permissions')) {
    function role_permissions(?string $role): array
    {
        if (empty($role)) {
            return [];
        }

        $permissions = config('role_permissions', []);
        $user = new \App\Models\User;
        $user->role = $role;
        $normalized = $user->normalizedRole();

        return $permissions[$normalized] ?? [];
    }
}

if (! function_exists('user_can_action')) {
    function user_can_action(string $action): bool
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }

        $allowed = role_permissions($user->role)['actions'] ?? [];
        if (in_array('*', $allowed, true)) {
            return true;
        }

        return in_array($action, $allowed, true);
    }
}

if (! function_exists('user_can_menu')) {
    function user_can_menu(string $menuKey): bool
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }

        $allowed = role_permissions($user->role)['menus'] ?? [];
        if (in_array('*', $allowed, true)) {
            return true;
        }

        return in_array($menuKey, $allowed, true);
    }
}
