<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Referrals table
        Schema::create('hospital_referrals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('appointment_id')->nullable();
            $table->string('referral_type'); // incoming, outgoing
            $table->string('referred_by')->nullable(); // doctor/clinic name (incoming)
            $table->string('referred_to')->nullable(); // doctor/clinic name (outgoing)
            $table->string('specialty')->nullable();
            $table->text('reason')->nullable();
            $table->date('referral_date');
            $table->string('status')->default('pending'); // pending, completed, cancelled
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->boolean('delete_status')->default(false);
            $table->timestamps();
        });

        // Medical certificates table
        Schema::create('hospital_medical_certificates', function (Blueprint $table) {
            $table->id();
            $table->string('certificate_no')->unique();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('appointment_id')->nullable();
            $table->unsignedBigInteger('doctor_id');
            $table->string('type'); // fitness, sick_leave, medical, custom
            $table->string('purpose')->nullable();
            $table->date('issue_date');
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
            $table->text('findings')->nullable();
            $table->text('recommendations')->nullable();
            $table->text('restrictions')->nullable();
            $table->boolean('is_fit')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->boolean('delete_status')->default(false);
            $table->timestamps();
        });

        // Appointment reminders table
        Schema::create('hospital_appointment_reminders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('appointment_id');
            $table->string('type')->default('email'); // email only for now
            $table->string('recipient_email')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->string('status')->default('pending'); // pending, sent, failed
            $table->text('message')->nullable();
            $table->boolean('delete_status')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hospital_appointment_reminders');
        Schema::dropIfExists('hospital_medical_certificates');
        Schema::dropIfExists('hospital_referrals');
    }
};
