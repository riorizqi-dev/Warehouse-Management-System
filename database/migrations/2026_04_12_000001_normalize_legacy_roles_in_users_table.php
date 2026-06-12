<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')->where('role', 'warehouse_admin')->update(['role' => 'staff_gudang']);
        DB::table('users')->where('role', 'sales_admin')->update(['role' => 'staff_penjualan']);
        DB::table('users')->where('role', 'user')->update(['role' => 'staff_gudang']);
    }

    public function down(): void
    {
        DB::table('users')->where('role', 'staff_gudang')->update(['role' => 'user']);
        DB::table('users')->where('role', 'staff_penjualan')->update(['role' => 'sales_admin']);
    }
};
