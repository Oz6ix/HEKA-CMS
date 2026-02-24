<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds vitals, clinical notes, and symptom columns needed by the EMR Workbench.
     */
    public function up(): void
    {
        Schema::table('hospital_patient_diagnosis', function (Blueprint $table) {
            // Vitals
            $table->string('height', 50)->nullable()->after('treatment_and_intervention_id');
            $table->string('height_unit', 20)->nullable()->default('cm')->after('height');
            $table->string('weight', 50)->nullable()->after('height_unit');
            $table->string('weight_unit', 20)->nullable()->default('kg')->after('weight');
            $table->string('systolic_bp', 20)->nullable()->after('weight_unit');
            $table->string('diastolic_bp', 20)->nullable()->after('systolic_bp');
            $table->string('pulse', 20)->nullable()->after('diastolic_bp');
            $table->string('temperature', 20)->nullable()->after('pulse');
            $table->string('temperature_unit', 10)->nullable()->default('C')->after('temperature');
            $table->string('spo2', 20)->nullable()->after('temperature_unit');
            $table->string('respiration', 20)->nullable()->after('spo2');
            $table->string('rbs', 20)->nullable()->after('respiration');

            // Symptom & Notes
            $table->unsignedBigInteger('symptom_type_id')->nullable()->after('rbs');
            $table->text('symptom')->nullable()->after('symptom_type_id');
            $table->text('description')->nullable()->after('symptom');
            $table->text('note')->nullable()->after('description');
            $table->datetime('checkup_at')->nullable()->after('note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hospital_patient_diagnosis', function (Blueprint $table) {
            $table->dropColumn([
                'height', 'height_unit', 'weight', 'weight_unit',
                'systolic_bp', 'diastolic_bp', 'pulse', 'temperature',
                'temperature_unit', 'spo2', 'respiration', 'rbs',
                'symptom_type_id', 'symptom', 'description', 'note', 'checkup_at'
            ]);
        });
    }
};
