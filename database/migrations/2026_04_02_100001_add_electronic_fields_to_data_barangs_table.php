<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('data_barangs', function (Blueprint $table) {
            if (! Schema::hasColumn('data_barangs', 'kode_barang')) {
                $table->string('kode_barang')->nullable()->unique()->after('id');
            }
            if (! Schema::hasColumn('data_barangs', 'merek')) {
                $table->string('merek')->nullable()->after('nama_barang');
            }
            if (! Schema::hasColumn('data_barangs', 'tipe_model')) {
                $table->string('tipe_model')->nullable()->after('merek');
            }
            if (! Schema::hasColumn('data_barangs', 'garansi_bulan')) {
                $table->unsignedSmallInteger('garansi_bulan')->default(0)->after('tipe_model');
            }
            if (! Schema::hasColumn('data_barangs', 'lokasi_rak')) {
                $table->string('lokasi_rak')->nullable()->after('garansi_bulan');
            }
            if (! Schema::hasColumn('data_barangs', 'stok_minimum')) {
                $table->unsignedInteger('stok_minimum')->default(5)->after('stok');
            }
            if (! Schema::hasColumn('data_barangs', 'harga_beli')) {
                $table->decimal('harga_beli', 14, 2)->default(0)->after('harga');
            }
            if (! Schema::hasColumn('data_barangs', 'harga_jual')) {
                $table->decimal('harga_jual', 14, 2)->nullable()->after('harga_beli');
            }
            if (! Schema::hasColumn('data_barangs', 'satuan')) {
                $table->string('satuan', 20)->default('unit')->after('harga_jual');
            }
        });
    }

    public function down(): void
    {
        Schema::table('data_barangs', function (Blueprint $table) {
            $columns = [
                'kode_barang',
                'merek',
                'tipe_model',
                'garansi_bulan',
                'lokasi_rak',
                'stok_minimum',
                'harga_beli',
                'harga_jual',
                'satuan',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('data_barangs', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
