<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add batch tracking fields to existing inventory
        Schema::table('hospital_inventory_items', function (Blueprint $table) {
            $table->string('batch_number')->nullable()->after('item_code');
            $table->date('expiry_date')->nullable()->after('date');
            $table->decimal('mrp', 10, 2)->default(0)->after('selling_price');
            $table->integer('reorder_level')->default(10)->after('mrp');
            $table->integer('used')->default(0)->change();
            $table->integer('balance')->default(0)->after('quantity');
        });

        // Create stock adjustments table
        Schema::create('hospital_stock_adjustments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_item_id');
            $table->string('type'); // damage, expiry, loss, return, correction
            $table->integer('quantity');
            $table->string('reason')->nullable();
            $table->unsignedBigInteger('adjusted_by')->nullable();
            $table->date('adjustment_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('hospital_inventory_items', function (Blueprint $table) {
            $table->dropColumn(['batch_number', 'expiry_date', 'mrp', 'reorder_level']);
        });

        Schema::dropIfExists('hospital_stock_adjustments');
    }
};
