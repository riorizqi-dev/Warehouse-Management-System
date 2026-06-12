<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status', 30)->default('unpaid')->after('status_pesanan')->index();
            }
            if (! Schema::hasColumn('orders', 'payment_proof_path')) {
                $table->string('payment_proof_path')->nullable()->after('payment_status');
            }
            if (! Schema::hasColumn('orders', 'payment_note')) {
                $table->text('payment_note')->nullable()->after('payment_proof_path');
            }
            if (! Schema::hasColumn('orders', 'payment_uploaded_at')) {
                $table->dateTime('payment_uploaded_at')->nullable()->after('payment_note');
            }
            if (! Schema::hasColumn('orders', 'payment_verified_by')) {
                $table->foreignId('payment_verified_by')->nullable()->after('payment_uploaded_at')->constrained('users')->nullOnDelete();
            }
            if (! Schema::hasColumn('orders', 'payment_verified_at')) {
                $table->dateTime('payment_verified_at')->nullable()->after('payment_verified_by');
            }
            if (! Schema::hasColumn('orders', 'payment_reject_note')) {
                $table->text('payment_reject_note')->nullable()->after('payment_verified_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'payment_verified_by')) {
                $table->dropConstrainedForeignId('payment_verified_by');
            }
            if (Schema::hasColumn('orders', 'payment_reject_note')) {
                $table->dropColumn('payment_reject_note');
            }
            if (Schema::hasColumn('orders', 'payment_verified_at')) {
                $table->dropColumn('payment_verified_at');
            }
            if (Schema::hasColumn('orders', 'payment_uploaded_at')) {
                $table->dropColumn('payment_uploaded_at');
            }
            if (Schema::hasColumn('orders', 'payment_note')) {
                $table->dropColumn('payment_note');
            }
            if (Schema::hasColumn('orders', 'payment_proof_path')) {
                $table->dropColumn('payment_proof_path');
            }
            if (Schema::hasColumn('orders', 'payment_status')) {
                $table->dropColumn('payment_status');
            }
        });
    }
};
