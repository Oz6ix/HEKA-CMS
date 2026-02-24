<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Patient Documents table (for clinical uploads + external results)
        Schema::create('patient_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('appointment_id')->nullable();
            $table->unsignedBigInteger('diagnosis_id')->nullable();
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->string('file_name');           // stored filename on disk
            $table->string('original_name');        // user-facing filename
            $table->string('file_type', 20);        // image, pdf
            $table->string('category', 40)->default('other');
            // categories: clinical_photo, referral, lab_report, imaging, consent, external_lab, external_imaging, other
            $table->text('notes')->nullable();
            $table->tinyInteger('delete_status')->default(0);
            $table->timestamps();
        });

        // 2) Add result fields to existing medical tests table
        Schema::table('hospital_patient_medical_tests', function (Blueprint $table) {
            $table->string('result_value')->nullable()->after('status');
            $table->string('result_unit', 50)->nullable()->after('result_value');
            $table->string('reference_range', 100)->nullable()->after('result_unit');
            $table->string('interpretation', 20)->nullable()->after('reference_range');
            // interpretation: normal, abnormal, critical
            $table->text('result_notes')->nullable()->after('interpretation');
            $table->date('result_date')->nullable()->after('result_notes');
            $table->unsignedBigInteger('result_entered_by')->nullable()->after('result_date');
            $table->timestamp('result_entered_at')->nullable()->after('result_entered_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_documents');

        Schema::table('hospital_patient_medical_tests', function (Blueprint $table) {
            $table->dropColumn([
                'result_value', 'result_unit', 'reference_range',
                'interpretation', 'result_notes', 'result_date',
                'result_entered_by', 'result_entered_at'
            ]);
        });
    }
};
