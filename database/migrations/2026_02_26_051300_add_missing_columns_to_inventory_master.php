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
        Schema::table('hospital_inventory_master', function (Blueprint $table) {
            if (!Schema::hasColumn('hospital_inventory_master', 'pharmacy_generic')) {
                $table->string('pharmacy_generic', 256)->nullable();
            }
            if (!Schema::hasColumn('hospital_inventory_master', 'pharmacy_dosage')) {
                $table->string('pharmacy_dosage', 256)->nullable();
            }
            if (!Schema::hasColumn('hospital_inventory_master', 'route')) {
                $table->string('route', 256)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hospital_inventory_master', function (Blueprint $table) {
            $table->dropColumn(['pharmacy_generic', 'pharmacy_dosage', 'route']);
        });
    }
};
