<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $columns = DB::select("SHOW COLUMNS FROM users WHERE Field = 'role'");
        if (! empty($columns)) {
            $column = $columns[0];
            if (strpos($column->Type, 'enum') !== false || (strpos($column->Type, 'varchar') !== false && (int) $column->Type < 20)) {
                DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(20) DEFAULT 'user'");
            }
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'user') DEFAULT 'user'");
        });
    }
};
