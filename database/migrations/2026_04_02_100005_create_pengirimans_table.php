<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengirimans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('barang_keluar_id')->nullable()->constrained('barang_keluars')->nullOnDelete();
            $table->string('metode_kirim')->nullable();
            $table->string('nama_ekspedisi')->nullable();
            $table->string('no_resi')->nullable();
            $table->dateTime('tanggal_kirim')->nullable();
            $table->dateTime('tanggal_terima')->nullable();
            $table->string('status_pengiriman')->default('belum_dibuat');
            $table->decimal('biaya_kirim', 14, 2)->default(0);
            $table->text('alamat_kirim_snapshot')->nullable();
            $table->string('penerima')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengirimans');
    }
};
