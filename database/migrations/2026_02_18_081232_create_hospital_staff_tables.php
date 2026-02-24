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
        // hospital_staff_roles
        Schema::create('hospital_staff_roles', function (Blueprint $table) {
            $table->id();
            $table->string('role', 255);
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('delete_status')->default(0);
            $table->timestamps();
        });

        // hospital_staff_departments
        Schema::create('hospital_staff_departments', function (Blueprint $table) {
            $table->id();
            $table->string('department', 255);
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('delete_status')->default(0);
            $table->timestamps();
        });

        // hospital_staff_designations
        Schema::create('hospital_staff_designations', function (Blueprint $table) {
            $table->id();
            $table->string('designation', 256);
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('delete_status')->default(0);
            $table->timestamps();
        });

        // hospital_staff_specialists
        Schema::create('hospital_staff_specialists', function (Blueprint $table) {
            $table->id();
            $table->string('specialist', 255);
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('delete_status')->default(0);
            $table->timestamps();
        });

        // hospital_user_groups
        Schema::create('hospital_user_groups', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->tinyInteger('admin_users')->default(0);
            $table->tinyInteger('staff')->default(0);
            $table->tinyInteger('patients')->default(0);
            $table->tinyInteger('appointments')->default(0);
            $table->tinyInteger('bills')->default(0);
            $table->tinyInteger('inventory')->default(0);
            $table->tinyInteger('appointment_report')->default(0);
            $table->tinyInteger('revenue_report')->default(0);
            $table->tinyInteger('general_settings')->default(0);
            $table->tinyInteger('user_groups')->default(0);
            $table->tinyInteger('notifications')->default(0);
            $table->tinyInteger('hospital_charges')->default(0);
            $table->tinyInteger('pharmacy')->default(0);
            $table->tinyInteger('phatology')->default(0);
            $table->tinyInteger('radiology')->default(0);
            $table->tinyInteger('suppliers')->default(0);
            $table->tinyInteger('configuration')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('delete_status')->default(0);
            $table->timestamps();
        });

        // hospital_staffs
        Schema::create('hospital_staffs', function (Blueprint $table) {
            $table->id();
            $table->string('staff_code', 255);
            $table->string('staff_directory', 256)->nullable();
            $table->integer('hospital_id')->default(0);
            $table->integer('role_id'); // FK to hospital_staff_roles
            $table->integer('department_id'); // FK to hospital_staff_departments
            $table->integer('designation_id'); // FK to hospital_staff_designations
            $table->string('name', 255);
            $table->string('phone', 100);
            $table->string('phone_alternative', 100)->nullable();
            $table->string('email', 255);
            $table->text('current_address')->nullable();
            $table->text('permanent_address')->nullable();
            $table->string('facebook_url', 255)->nullable();
            $table->string('linkedin_url', 255)->nullable();
            $table->string('twitter_url', 255)->nullable();
            $table->string('instagram_url', 255)->nullable();
            $table->integer('specialist_id')->default(0); // FK to hospital_staff_specialists (optional)
            $table->tinyInteger('gender')->default(0);
            $table->integer('maritial_status')->default(0);
            $table->integer('blood_group')->default(0);
            $table->date('dob')->nullable();
            $table->string('dob_str', 255)->nullable();
            $table->date('date_join')->nullable();
            $table->string('date_join_str', 255)->nullable();
            $table->text('qualification')->nullable();
            $table->text('work_experience')->nullable();
            $table->text('note')->nullable();
            $table->tinyInteger('permission_admin_access')->default(0);
            $table->integer('status')->default(1);
            $table->integer('delete_status')->default(0);
            $table->timestamps();
        });

        // hospital_staff_documents
        Schema::create('hospital_staff_documents', function (Blueprint $table) {
            $table->id();
            $table->integer('staff_id'); // FK to hospital_staffs
            $table->longText('staff_image')->nullable();
            $table->string('resume', 500)->nullable();
            $table->string('document', 500)->nullable();
            $table->string('document_file_type', 100)->nullable();
            $table->string('resume_file_type', 100)->nullable();
            $table->string('image_file_type', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospital_staff_documents');
        Schema::dropIfExists('hospital_staffs');
        Schema::dropIfExists('hospital_user_groups');
        Schema::dropIfExists('hospital_staff_specialists');
        Schema::dropIfExists('hospital_staff_designations');
        Schema::dropIfExists('hospital_staff_departments');
        Schema::dropIfExists('hospital_staff_roles');
    }
};
