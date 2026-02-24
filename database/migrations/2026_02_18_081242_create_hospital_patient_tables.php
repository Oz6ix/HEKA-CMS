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
        // hospital_blood_groups
        Schema::create('hospital_blood_groups', function (Blueprint $table) {
            $table->id();
            $table->string('blood_group', 100)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('delete_status')->default(0);
            $table->timestamps();
        });

        // hospital_patients
        Schema::create('hospital_patients', function (Blueprint $table) {
            $table->id();
            $table->string('patient_code', 256);
            $table->integer('hospital_id')->default(0);
            $table->string('patient_folder_name', 256);
            $table->string('name', 256);
            $table->string('phone', 256);
            $table->string('phone_alternative', 256)->nullable();
            $table->string('email', 256)->nullable();
            $table->string('password', 256)->nullable();
            $table->string('dob', 256)->nullable();
            $table->string('dob_str', 256)->nullable();
            $table->tinyInteger('gender')->nullable();
            $table->string('guardian_name', 256)->nullable();
            $table->string('blood_group', 256)->nullable();
            $table->tinyInteger('marital_status')->nullable();
            $table->text('any_known_allergies')->nullable();
            $table->string('patient_photo', 500)->nullable();
            $table->text('address')->nullable();
            $table->integer('age_year')->nullable();
            $table->integer('age_month')->nullable();
            $table->text('remark')->nullable();
            $table->tinyInteger('add_status')->default(0)->comment('0:admin 1:patient');
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('reset_pwd_status')->default(0);
            $table->tinyInteger('delete_status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospital_patients');
        Schema::dropIfExists('hospital_blood_groups');
    }
};
