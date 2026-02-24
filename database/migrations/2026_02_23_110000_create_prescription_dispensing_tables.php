<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // External Prescriptions (walk-in / outside Rx)
        Schema::create('hospital_external_prescriptions', function (Blueprint $table) {
            $table->id();
            $table->string('rx_code')->unique();
            $table->string('patient_name');
            $table->string('patient_phone')->nullable();
            $table->unsignedBigInteger('patient_id')->nullable(); // link to existing patient if known
            $table->string('doctor_name')->nullable();
            $table->string('doctor_license_no')->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('instructions')->nullable();
            $table->string('rx_image')->nullable(); // uploaded Rx photo
            $table->string('type')->default('paper'); // paper, digital
            $table->date('rx_date');
            $table->string('status')->default('pending'); // pending, dispensed, partial, cancelled
            $table->unsignedBigInteger('created_by')->nullable();
            $table->boolean('delete_status')->default(false);
            $table->timestamps();
        });

        // Dispensed Items (tracks what drugs were actually dispensed)
        Schema::create('hospital_dispensed_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prescription_id')->nullable(); // internal prescription
            $table->unsignedBigInteger('external_prescription_id')->nullable(); // external prescription
            $table->unsignedBigInteger('pharmacy_id'); // drug from pharmacy settings
            $table->unsignedBigInteger('inventory_item_id')->nullable(); // stock batch used
            $table->string('drug_name');
            $table->integer('quantity_prescribed')->default(0);
            $table->integer('quantity_dispensed')->default(0);
            $table->boolean('is_partial')->default(false);
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->decimal('total_price', 10, 2)->default(0);
            $table->unsignedBigInteger('dispensed_by')->nullable();
            $table->timestamp('dispensed_at')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('delete_status')->default(false);
            $table->timestamps();
        });

        // Pharmacy Sales (invoices for walk-in / OTC sales)
        Schema::create('hospital_pharmacy_sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique();
            $table->unsignedBigInteger('patient_id')->nullable();
            $table->unsignedBigInteger('external_prescription_id')->nullable();
            $table->string('customer_name');
            $table->string('customer_phone')->nullable();
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->string('payment_method')->default('cash'); // cash, card, upi
            $table->string('status')->default('completed'); // completed, refunded
            $table->unsignedBigInteger('created_by')->nullable();
            $table->boolean('delete_status')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hospital_pharmacy_sales');
        Schema::dropIfExists('hospital_dispensed_items');
        Schema::dropIfExists('hospital_external_prescriptions');
    }
};
