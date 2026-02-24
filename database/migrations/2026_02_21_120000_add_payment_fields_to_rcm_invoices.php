<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rcm_invoices', function (Blueprint $table) {
            $table->string('payment_mode')->nullable()->after('payment_status');
            $table->string('payment_reference')->nullable()->after('payment_mode');
            $table->timestamp('paid_at')->nullable()->after('payment_reference');
            $table->unsignedBigInteger('paid_by')->nullable()->after('paid_at');
            $table->boolean('is_credit')->default(0)->after('paid_by');
            $table->date('credit_due_date')->nullable()->after('is_credit');
            $table->timestamp('credit_settled_at')->nullable()->after('credit_due_date');
        });
    }

    public function down(): void
    {
        Schema::table('rcm_invoices', function (Blueprint $table) {
            $table->dropColumn([
                'payment_mode', 'payment_reference', 'paid_at', 'paid_by',
                'is_credit', 'credit_due_date', 'credit_settled_at'
            ]);
        });
    }
};
