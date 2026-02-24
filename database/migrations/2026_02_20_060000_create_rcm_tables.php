<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Unified RCM billing tables.
     * rcm_invoices = invoice header (one per bill)
     * rcm_bill_items = line items (many per invoice)
     */
    public function up(): void
    {
        Schema::create('rcm_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('bill_number')->unique();
            $table->unsignedBigInteger('appointment_id');
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('doctor_id');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount_pct', 5, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('tax_pct', 5, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('net_amount', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->string('payment_status')->default('pending'); // pending, paid, partial
            $table->dateTime('bill_date');
            $table->integer('status')->default(1);
            $table->integer('delete_status')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('rcm_bill_items', function (Blueprint $table) {
            $table->id();
            $table->string('bill_number');
            $table->unsignedBigInteger('appointment_id');
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('doctor_id');
            $table->unsignedBigInteger('diagnosis_id')->nullable();
            // Service category: consultation, pharmacy, pathology, radiology, procedure, consumable, other
            $table->string('service_category');
            $table->string('item_description');
            // Polymorphic reference to source record
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('reference_type')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('line_total', 12, 2)->default(0);
            $table->dateTime('bill_date');
            $table->integer('status')->default(1);
            $table->integer('delete_status')->default(0);
            $table->timestamps();

            $table->index('bill_number');
            $table->index('appointment_id');
            $table->index('service_category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rcm_bill_items');
        Schema::dropIfExists('rcm_invoices');
    }
};
