<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // hospital_inventory_category
        Schema::create('hospital_inventory_category', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id');
            $table->string('inventory_name', 256);
            $table->longText('description')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('delete_status')->default(0);
            $table->timestamps();
        });

        // hospital_inventory_master
        Schema::create('hospital_inventory_master', function (Blueprint $table) {
            $table->id();
            $table->string('item_name', 256);
            $table->string('master_code', 256)->nullable();
            $table->integer('inventory_category_id');
            $table->string('pharmacy_generic', 256)->nullable();
            $table->string('pharmacy_dosage', 256)->nullable();
            $table->string('route', 256)->nullable();
            $table->longText('description')->nullable();
            $table->integer('inventory_unit')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('delete_status')->default(0);
            $table->timestamps();
        });
        
        // hospital_inventory_items
        Schema::create('hospital_inventory_items', function (Blueprint $table) {
            $table->id();
            $table->string('item_code', 100);
            $table->integer('inventory_master_id');
            $table->integer('supplier_id');
            $table->string('quantity', 256);
            $table->decimal('purchase_price', 10, 0)->default(0);
            $table->decimal('selling_price', 10, 0)->default(0);
            $table->date('date');
            $table->string('date_str', 256)->nullable();
            $table->longText('description')->nullable();
            $table->string('document', 500)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('delete_status')->default(0);
            $table->timestamps();
        });
        
        // hospital_settings_pharmacy_category
        Schema::create('hospital_settings_pharmacy_category', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id');
            $table->string('name', 256);
            $table->longText('description')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('delete_status')->default(0);
            $table->timestamps();
        });
        
        // hospital_settings_pharmacy
        Schema::create('hospital_settings_pharmacy', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('pharmacy_category_id');
            $table->string('title', 500);
            $table->string('code', 256);
            $table->string('company_name', 150);
            $table->string('unit', 200);
            $table->string('quantity', 200);
            $table->decimal('price', 20, 2);
            $table->string('photo', 200);
            $table->longText('note')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('import_status')->default(0)->nullable();
            $table->tinyInteger('import_update_status')->default(0);
            $table->tinyInteger('delete_status')->default(0);
            $table->timestamps();
        });
        
        // hospital_suppliers
        Schema::create('hospital_suppliers', function (Blueprint $table) {
             $table->id();
             $table->string('supplier_name', 256);
             $table->string('supplier_code', 256);
             $table->string('phone', 256)->nullable();
             $table->string('phone_alternative', 256)->nullable();
             $table->string('email', 256)->nullable();
             $table->text('address')->nullable();
             $table->tinyInteger('status')->default(1);
             $table->tinyInteger('delete_status')->default(0);
             $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospital_suppliers');
        Schema::dropIfExists('hospital_settings_pharmacy');
        Schema::dropIfExists('hospital_settings_pharmacy_category');
        Schema::dropIfExists('hospital_inventory_items');
        Schema::dropIfExists('hospital_inventory_master');
        Schema::dropIfExists('hospital_inventory_category');
    }
};
