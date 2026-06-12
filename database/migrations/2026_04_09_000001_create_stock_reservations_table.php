<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('order_item_id')->constrained('order_items')->cascadeOnDelete();
            $table->foreignId('data_barang_id')->constrained('data_barangs')->restrictOnDelete();
            $table->unsignedInteger('qty_reserved');
            $table->string('status')->default('active');
            $table->foreignId('reserved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('reserved_at')->nullable();
            $table->dateTime('fulfilled_at')->nullable();
            $table->dateTime('released_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['data_barang_id', 'status']);
            $table->index(['order_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_reservations');
    }
};
