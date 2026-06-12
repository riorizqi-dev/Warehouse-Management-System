<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'superadmin@thevoid.com'],
            [
                'name' => 'Super Admin',
                'username' => 'superadmin',
                'email' => 'superadmin@thevoid.com',
                'password' => Hash::make('superadmin123'),
                'role' => 'superadmin',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@thevoid.com'],
            [
                'name' => 'Administrator',
                'username' => 'admin',
                'email' => 'admin@thevoid.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'gudang@thevoid.com'],
            [
                'name' => 'Staff Gudang',
                'username' => 'staffgudang',
                'email' => 'gudang@thevoid.com',
                'password' => Hash::make('gudang123'),
                'role' => 'staff_gudang',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'cs@thevoid.com'],
            [
                'name' => 'Staff Penjualan',
                'username' => 'staffcs',
                'email' => 'cs@thevoid.com',
                'password' => Hash::make('cs123456'),
                'role' => 'staff_penjualan',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'kurir@thevoid.com'],
            [
                'name' => 'Kurir Gudang',
                'username' => 'kurir',
                'email' => 'kurir@thevoid.com',
                'password' => Hash::make('kurir123'),
                'role' => 'kurir',
                'email_verified_at' => now(),
            ]
        );
    }
}
