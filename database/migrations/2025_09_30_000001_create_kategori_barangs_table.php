<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_barangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
        if (Schema::hasTable('data_barangs') && ! Schema::hasColumn('data_barangs', 'kategori_id')) {
            Schema::table('data_barangs', function (Blueprint $table) {
                $table->foreignId('kategori_id')->nullable()->constrained('kategori_barangs')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('data_barangs') && Schema::hasColumn('data_barangs', 'kategori_id')) {
            Schema::table('data_barangs', function (Blueprint $table) {
                $table->dropConstrainedForeignId('kategori_id');
            });
        }
        Schema::dropIfExists('kategori_barangs');
    }
};
