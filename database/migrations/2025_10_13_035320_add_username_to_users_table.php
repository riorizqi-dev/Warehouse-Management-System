<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'username')) {
                $table->string('username')->unique()->after('name');
            }
            if (! Schema::hasColumn('users', 'role')) {
                $table->string('role', 20)->default('user')->after('name');
            } else {
                DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(20) DEFAULT 'user'");
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'role']);
        });
    }
};
