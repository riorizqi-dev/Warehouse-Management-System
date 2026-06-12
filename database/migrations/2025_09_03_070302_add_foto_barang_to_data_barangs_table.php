<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('data_barangs', function (Blueprint $table) {
            $table->string('foto_barang')->nullable()->after('harga');
        });
    }

    public function down(): void
    {
        Schema::table('data_barangs', function (Blueprint $table) {
            $table->dropColumn('foto_barang');
        });
    }
};
