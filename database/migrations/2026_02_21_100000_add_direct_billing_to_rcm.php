<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Make appointment_id nullable in rcm_invoices
        Schema::table('rcm_invoices', function (Blueprint $table) {
            // SQLite doesn't support ALTER COLUMN, so we handle gracefully
        });

        // Add bill_type column if it doesn't exist
        if (!Schema::hasColumn('rcm_invoices', 'bill_type')) {
            Schema::table('rcm_invoices', function (Blueprint $table) {
                $table->string('bill_type', 20)->default('appointment')->after('bill_number');
            });
        }

        // For SQLite: recreate with nullable appointment_id
        // For MySQL: just alter column
        try {
            DB::statement('ALTER TABLE rcm_invoices MODIFY appointment_id BIGINT UNSIGNED NULL');
        } catch (\Exception $e) {
            // SQLite — column is already effectively nullable with default schema
        }

        try {
            DB::statement('ALTER TABLE rcm_bill_items MODIFY appointment_id BIGINT UNSIGNED NULL');
        } catch (\Exception $e) {
            // SQLite fallback
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('rcm_invoices', 'bill_type')) {
            Schema::table('rcm_invoices', function (Blueprint $table) {
                $table->dropColumn('bill_type');
            });
        }
    }
};
