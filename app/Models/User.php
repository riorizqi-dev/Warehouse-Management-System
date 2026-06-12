<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    public const ROLE_SUPERADMIN = 'superadmin';

    public const ROLE_ADMIN = 'admin';

    public const ROLE_STAFF_GUDANG = 'staff_gudang';

    public const ROLE_STAFF_PENJUALAN = 'staff_penjualan';

    public const ROLE_KURIR = 'kurir';

    public const ROLE_CUSTOMER = 'customer';

    private const LEGACY_ROLE_ALIASES = [
        'warehouse_admin' => self::ROLE_STAFF_GUDANG,
        'sales_admin' => self::ROLE_STAFF_PENJUALAN,
        'user' => self::ROLE_STAFF_GUDANG,
    ];

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role', // pastikan kolom role udah ada di migration
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public static function roleOptions(): array
    {
        return [
            self::ROLE_SUPERADMIN => 'Superadmin',
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_STAFF_GUDANG => 'Staff Gudang',
            self::ROLE_STAFF_PENJUALAN => 'Staff Penjualan / CS',
            self::ROLE_KURIR => 'Kurir / Pengiriman',
            self::ROLE_CUSTOMER => 'Customer',
        ];
    }

    public function normalizedRole(): string
    {
        $role = strtolower(trim((string) ($this->role ?? '')));

        return self::LEGACY_ROLE_ALIASES[$role] ?? $role;
    }

    public function hasRole(string $role): bool
    {
        return $this->normalizedRole() === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        $normalized = $this->normalizedRole();

        return in_array($normalized, $roles, true);
    }

    public function roleLabel(): string
    {
        return self::roleOptions()[$this->normalizedRole()] ?? ucfirst(str_replace('_', ' ', $this->normalizedRole()));
    }

    public function isSuperAdmin()
    {
        return $this->hasRole(self::ROLE_SUPERADMIN);
    }

    public function isAdmin()
    {
        return $this->hasRole(self::ROLE_ADMIN);
    }

    public function isWarehouseStaff()
    {
        return $this->hasRole(self::ROLE_STAFF_GUDANG);
    }

    public function isSalesStaff()
    {
        return $this->hasRole(self::ROLE_STAFF_PENJUALAN);
    }

    public function isCourier()
    {
        return $this->hasRole(self::ROLE_KURIR);
    }

    public function isCustomer()
    {
        return $this->hasRole(self::ROLE_CUSTOMER);
    }

    public function canManageWarehouse()
    {
        return $this->hasAnyRole([
            self::ROLE_SUPERADMIN,
            self::ROLE_ADMIN,
            self::ROLE_STAFF_GUDANG,
        ]);
    }

    public function canManageSales()
    {
        return $this->hasAnyRole([
            self::ROLE_SUPERADMIN,
            self::ROLE_ADMIN,
            self::ROLE_STAFF_PENJUALAN,
        ]);
    }

    public function canManageShipping()
    {
        return $this->hasAnyRole([
            self::ROLE_SUPERADMIN,
            self::ROLE_ADMIN,
            self::ROLE_KURIR,
        ]);
    }

    public function canManageMasterData(): bool
    {
        return $this->hasAnyRole([
            self::ROLE_SUPERADMIN,
            self::ROLE_ADMIN,
            self::ROLE_STAFF_GUDANG,
            self::ROLE_STAFF_PENJUALAN,
        ]);
    }

    public function isUser()
    {
        return $this->role === 'user' || $this->role === null;
    }

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    public function ordersCreated()
    {
        return $this->hasMany(Order::class, 'created_by');
    }

    public function ordersApproved()
    {
        return $this->hasMany(Order::class, 'approved_by');
    }

    public function barangKeluars()
    {
        return $this->hasMany(BarangKeluar::class);
    }

    public function notifications()
    {
        return $this->hasMany(UserNotification::class, 'user_id');
    }

    public function unreadNotifications()
    {
        return $this->notifications()->where('is_read', false);
    }
}
