<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chatbot_logs', function (Blueprint $table) {
            $table->id();
            $table->string('session_id', 120)->nullable()->index();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('question');
            $table->text('answer');
            $table->string('intent', 60)->nullable()->index();
            $table->foreignId('matched_product_id')->nullable()->constrained('data_barangs')->nullOnDelete();
            $table->foreignId('matched_faq_id')->nullable()->constrained('faqs')->nullOnDelete();
            $table->decimal('confidence', 5, 2)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 255)->nullable();
            $table->timestamps();

            $table->index(['created_at', 'intent']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_logs');
    }
};
