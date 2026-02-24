<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Make radiology_test_id and pathology_test_id nullable
     * so medical test orders can be created for either type independently.
     */
    public function up(): void
    {
        // SQLite doesn't support ALTER COLUMN well, so recreate the table concept
        // Using Schema::table with nullable change
        Schema::table('hospital_patient_medical_tests', function (Blueprint $table) {
            $table->integer('radiology_test_id')->nullable()->default(null)->change();
            $table->integer('pathology_test_id')->nullable()->default(null)->change();
        });
    }

    public function down(): void
    {
        Schema::table('hospital_patient_medical_tests', function (Blueprint $table) {
            $table->integer('radiology_test_id')->nullable(false)->change();
            $table->integer('pathology_test_id')->nullable(false)->change();
        });
    }
};
