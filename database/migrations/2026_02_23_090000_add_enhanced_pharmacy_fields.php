<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hospital_settings_pharmacy', function (Blueprint $table) {
            $table->string('generic_name')->nullable()->after('title');
            $table->string('brand_name')->nullable()->after('generic_name');
            $table->string('strength')->nullable()->after('brand_name');
            $table->string('form')->nullable()->after('strength');
            $table->string('manufacturer')->nullable()->after('form');
            $table->string('schedule')->default('OTC')->after('manufacturer');
            $table->string('medicine_type')->default('allopathy')->after('schedule');
            $table->string('barcode')->nullable()->after('medicine_type');
            $table->decimal('mrp', 10, 2)->default(0)->after('price');
            $table->integer('generic_group_id')->nullable()->after('mrp');
            $table->boolean('is_generic')->default(false)->after('generic_group_id');
            $table->string('hsn_code')->nullable()->after('is_generic');
        });
    }

    public function down(): void
    {
        Schema::table('hospital_settings_pharmacy', function (Blueprint $table) {
            $table->dropColumn([
                'generic_name', 'brand_name', 'strength', 'form',
                'manufacturer', 'schedule', 'medicine_type', 'barcode',
                'mrp', 'generic_group_id', 'is_generic', 'hsn_code'
            ]);
        });
    }
};
