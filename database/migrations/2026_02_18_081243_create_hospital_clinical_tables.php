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
        // hospital_appointments
        Schema::create('hospital_appointments', function (Blueprint $table) {
            $table->id();
            $table->integer('patient_id');
            $table->string('patient_name', 100)->nullable();
            $table->integer('hospital_id')->default(0);
            $table->integer('tpa_id')->default(0);
            $table->string('case_number', 250);
            $table->date('appointment_date');
            $table->string('appointment_date_str', 250);
            $table->string('reference', 250)->nullable();
            $table->integer('doctor_staff_id');
            $table->integer('casualty_id')->default(0);
            $table->integer('add_status')->default(0)->comment('0:admin 1:patient');
            $table->tinyInteger('pharmacy_bill_status')->default(0);
            $table->tinyInteger('pathology_bill_status')->default(0);
            $table->tinyInteger('radiology_bill_status')->default(0);
            $table->tinyInteger('consumable_bill_status')->default(0);
            $table->tinyInteger('other_bill_status')->default(0);
            $table->tinyInteger('diagnosis_status')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('delete_status')->default(0);
            $table->timestamps();
        });

        // hospital_appointment_basics_details
        Schema::create('hospital_appointment_basics_details', function (Blueprint $table) {
            $table->id();
            $table->integer('appointment_id');
            $table->integer('patient_id');
            $table->integer('doctor_staff_id')->default(0);
            $table->string('height', 256)->nullable();
            $table->string('weight', 256)->nullable();
            $table->string('bp', 256)->nullable();
            $table->string('pulse', 256)->nullable();
            $table->string('temperature', 256)->nullable();
            $table->integer('height_unit')->nullable();
            $table->integer('weight_unit')->nullable();
            $table->integer('systolic_bp')->nullable();
            $table->integer('diastolic_bp')->nullable();
            $table->integer('temperature_unit')->nullable();
            $table->string('respiration', 256)->nullable();
            $table->integer('symptom_type_id')->default(0);
            $table->string('symptom', 256)->nullable();
            $table->longText('description')->nullable();
            $table->longText('note')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('delete_status')->default(0);
            $table->timestamps();
        });
        
        // hospital_patient_brief_notes
        Schema::create('hospital_patient_brief_notes', function (Blueprint $table) {
            $table->id();
            $table->integer('appointment_id');
            $table->integer('staff_id')->nullable();
            $table->integer('patient_id');
            $table->longText('cheif_complaint')->nullable();
            $table->tinyInteger('cheif_complaint_status')->default(0);
            $table->longText('history_of_present_illness')->nullable();
            $table->tinyInteger('history_of_present_illness_status')->default(0);
            $table->longText('past_history')->nullable();
            $table->tinyInteger('past_history_status')->default(0);
            $table->longText('physical_examiniation')->nullable();
            $table->tinyInteger('physical_examiniation_status')->default(0);
            $table->integer('diagnosis_id')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('delete_status')->default(0);
            $table->timestamps();
        });

        // hospital_patient_diagnosis
        Schema::create('hospital_patient_diagnosis', function (Blueprint $table) {
             $table->id();
             $table->integer('appointment_id');
             $table->integer('appointment_basic_id');
             $table->integer('patient_id');
             $table->integer('staff_id');
             $table->text('diagnosis')->nullable();
             $table->tinyInteger('icd_diagnosis')->default(1)->nullable();
             $table->integer('treatment_and_intervention_id')->nullable();
             $table->integer('submitted_staff_id');
             $table->tinyInteger('status')->default(1);
             $table->tinyInteger('delete_status')->default(0);
             $table->timestamps();
        });
        
         // hospital_patient_diagnosis_report
        Schema::create('hospital_patient_diagnosis_report', function (Blueprint $table) {
             $table->id();
             $table->integer('appointment_id');
             $table->integer('diagnosis_id');
             $table->string('report_name', 500)->nullable();
             $table->tinyInteger('status')->default(1);
             $table->tinyInteger('delete_status')->default(0);
             $table->timestamps();
        });

        // hospital_patient_medical_tests
        Schema::create('hospital_patient_medical_tests', function (Blueprint $table) {
             $table->id();
             $table->integer('appointment_id');
             $table->integer('staff_id');
             $table->integer('patient_id');
             $table->integer('diagnosis_id');
             $table->string('test_name', 500)->nullable();
             $table->bigInteger('radiology_test_id');
             $table->bigInteger('pathology_test_id');
             $table->integer('reffered_center_id')->nullable();
             $table->tinyInteger('status')->default(1);
             $table->tinyInteger('delete_status')->default(0);
             $table->timestamps();
        });

        // hospital_patient_prescriptions
        Schema::create('hospital_patient_prescriptions', function (Blueprint $table) {
             $table->id();
             $table->integer('appointment_id');
             $table->string('drug_name', 500)->nullable();
             $table->bigInteger('drug_id');
             $table->decimal('quantity', 10, 0)->nullable();
             $table->integer('unit_id')->nullable();
             $table->integer('frequency_id')->nullable();
             $table->string('no_of_days', 256)->nullable();
             $table->integer('staff_id');
             $table->integer('patient_id');
             $table->integer('diagnosis_id')->nullable();
             $table->tinyInteger('status')->default(1);
             $table->tinyInteger('delete_status')->default(0);
             $table->timestamps();
        });

        // hospital_patient_bills
        Schema::create('hospital_patient_bills', function (Blueprint $table) {
             $table->id();
             $table->string('bill_number', 100);
             $table->integer('appointment_id');
             $table->integer('diagnosis_id');
             $table->date('bill_date')->nullable();
             $table->integer('patient_id');
             $table->integer('doctor_id');
             $table->tinyInteger('bill_type')->comment('1-Hospital Charge, 2-Pharmacy Bill, 3-Pathology bill, 4-Radiology bill, 5-Others');
             $table->integer('prescription_id')->nullable();
             $table->float('medicine_price')->default(0);
             $table->integer('hospital_charge_id')->nullable();
             $table->float('hospital_charge_price')->default(0);
             $table->tinyInteger('hospital_charge_status')->default(0);
             $table->float('total');
             $table->text('notes')->nullable();
             $table->float('discount')->default(0);
             $table->float('discount_price')->default(0);
             $table->float('tax')->default(0);
             $table->float('tax_price')->default(0);
             $table->float('net_amount')->default(0);
             $table->tinyInteger('status')->default(1);
             $table->tinyInteger('delete_status')->default(0);
             $table->timestamps();
        });

        // hospital_patient_bills_consumable
        Schema::create('hospital_patient_bills_consumable', function (Blueprint $table) {
             $table->id();
             $table->string('bill_number', 100);
             $table->integer('appointment_id');
             $table->integer('diagnosis_id');
             $table->date('bill_date')->nullable();
             $table->integer('patient_id');
             $table->integer('doctor_id');
             $table->tinyInteger('bill_type')->comment('1-Hospital Charge, 2-Pharmacy Bill, 3-Pathology bill, 4-Radiology bill, 5-Consumable');
             $table->integer('consumable_used_id')->nullable();
             $table->bigInteger('consumable_item_id');
             $table->float('consumable_price')->default(0);
             $table->float('total');
             $table->text('notes')->nullable();
             $table->float('discount')->default(0);
             $table->float('discount_price')->default(0);
             $table->float('tax')->default(0);
             $table->float('tax_price')->default(0);
             $table->float('net_amount')->default(0);
             $table->tinyInteger('status')->default(1);
             $table->tinyInteger('delete_status')->default(0);
             $table->timestamps();
        });

        // hospital_patient_bills_pathology
        Schema::create('hospital_patient_bills_pathology', function (Blueprint $table) {
             $table->id();
             $table->string('bill_number', 100);
             $table->integer('appointment_id');
             $table->integer('diagnosis_id');
             $table->date('bill_date')->nullable();
             $table->integer('patient_id');
             $table->integer('doctor_id');
             $table->tinyInteger('bill_type')->comment('1-Hospital Charge, 2-Pharmacy Bill, 3-Pathology bill, 4-Radiology bill, 5-Others');
             $table->integer('test_id')->nullable();
             $table->bigInteger('pathology_test_id');
             $table->float('test_price')->default(0);
             $table->float('total');
             $table->text('notes')->nullable();
             $table->float('discount')->default(0);
             $table->float('discount_price')->default(0);
             $table->float('tax')->default(0);
             $table->float('tax_price')->default(0);
             $table->float('net_amount')->default(0);
             $table->tinyInteger('status')->default(1);
             $table->tinyInteger('delete_status')->default(0);
             $table->timestamps();
        });

        // hospital_patient_bills_radiology
        Schema::create('hospital_patient_bills_radiology', function (Blueprint $table) {
             $table->id();
             $table->string('bill_number', 100);
             $table->integer('appointment_id');
             $table->integer('diagnosis_id');
             $table->date('bill_date')->nullable();
             $table->integer('patient_id');
             $table->integer('doctor_id');
             $table->tinyInteger('bill_type')->comment('1-Hospital Charge, 2-Pharmacy Bill, 3-Pathology bill, 4-Radiology bill, 5-Others');
             $table->integer('test_id')->nullable();
             $table->bigInteger('radiology_test_id');
             $table->float('test_price')->default(0);
             $table->float('total');
             $table->text('notes')->nullable();
             $table->float('discount')->default(0);
             $table->float('discount_price')->default(0);
             $table->float('tax')->default(0);
             $table->float('tax_price')->default(0);
             $table->float('net_amount')->default(0);
             $table->tinyInteger('status')->default(1);
             $table->tinyInteger('delete_status')->default(0);
             $table->timestamps();
        });
        
        // hospital_settings_hospital_charge_category
        Schema::create('hospital_settings_hospital_charge_category', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id');
            $table->string('name', 256);
            $table->longText('description')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('delete_status')->default(0);
            $table->timestamps();
        });
        
        // hospital_settings_hospital_charges
        Schema::create('hospital_settings_hospital_charges', function (Blueprint $table) {
            $table->id();
            $table->integer('hospital_charge_category_id');
            $table->string('title', 500);
            $table->string('code', 256);
            $table->decimal('standard_charge', 10, 0);
            $table->longText('description')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('delete_status')->default(0);
            $table->timestamps();
        });
        
        // hospital_patient_medical_consumable_used
        Schema::create('hospital_patient_medical_consumable_used', function (Blueprint $table) {
            $table->id();
            $table->integer('appointment_id');
            $table->integer('staff_id');
            $table->integer('patient_id');
            $table->string('item', 500)->nullable();
            $table->string('item_name', 200);
            $table->decimal('quantity', 10, 0)->nullable();
            $table->integer('unit_id')->nullable();
            $table->integer('diagnosis_id');
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
        Schema::dropIfExists('hospital_patient_medical_consumable_used');
        Schema::dropIfExists('hospital_settings_hospital_charges');
        Schema::dropIfExists('hospital_settings_hospital_charge_category');
        Schema::dropIfExists('hospital_patient_bills_radiology');
        Schema::dropIfExists('hospital_patient_bills_pathology');
        Schema::dropIfExists('hospital_patient_bills_consumable');
        Schema::dropIfExists('hospital_patient_bills');
        Schema::dropIfExists('hospital_patient_prescriptions');
        Schema::dropIfExists('hospital_patient_medical_tests');
        Schema::dropIfExists('hospital_patient_diagnosis_report');
        Schema::dropIfExists('hospital_patient_diagnosis');
        Schema::dropIfExists('hospital_patient_brief_notes');
        Schema::dropIfExists('hospital_appointment_basics_details');
        Schema::dropIfExists('hospital_appointments');
    }
};
