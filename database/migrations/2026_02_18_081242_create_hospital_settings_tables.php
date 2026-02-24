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
        // hospital_hospital
        Schema::create('hospital_hospital', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_name', 200);
            $table->string('hospital_code', 200);
            $table->timestamps();
        });

        // hospital_settings_general_infos
        Schema::create('hospital_settings_general_infos', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_name', 100);
            $table->text('hospital_address')->nullable();
            $table->string('contact_email', 50);
            $table->string('contact_phone', 20);
            $table->string('alternative_phone', 100)->nullable();
            $table->string('hospital_code', 100)->nullable();
            $table->string('facebook_url', 100)->nullable();
            $table->string('twitter_url', 100)->nullable();
            $table->string('instagram_url', 128)->nullable();
            $table->string('youtube_url', 128)->nullable();
            $table->string('linkedin_url', 255)->nullable();
            $table->timestamps();
        });

        // hospital_settings_site_logos
        Schema::create('hospital_settings_site_logos', function (Blueprint $table) {
            $table->id();
            $table->string('logo_desktop', 500)->nullable();
            $table->string('logo_mobile_2x', 500)->nullable();
            $table->string('logo_mobile', 500)->nullable();
            $table->string('favicon', 500)->nullable();
            $table->string('homepage_title', 100)->nullable();
            $table->string('homepage_keywords', 500)->nullable();
            $table->string('homepage_description', 500)->nullable();
            $table->text('google_analytics')->nullable();
            $table->text('footer_copy_right')->nullable();
            $table->timestamps();
        });

        // hospital_settings_notifications
        Schema::create('hospital_settings_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('patient_registration_email', 256)->nullable();
            $table->string('patient_phone', 256)->nullable();
            $table->string('appointment_booking_email', 256)->nullable();
            $table->string('appointment_phone', 256)->nullable();
            $table->string('inventory_stock_email', 256)->nullable();
            $table->string('inventory_stock_phone', 256)->nullable();
            $table->timestamps();
        });

        // hospital_units
        Schema::create('hospital_units', function (Blueprint $table) {
            $table->id();
            $table->string('unit', 256);
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('delete_status')->default(0);
            $table->timestamps();
        });

        // hospital_frequency
        Schema::create('hospital_frequency', function (Blueprint $table) {
            $table->id();
            $table->string('frequency', 256);
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('delete_status')->default(0);
            $table->timestamps();
        });

        // hospital_symptom_type
        Schema::create('hospital_symptom_type', function (Blueprint $table) {
            $table->id();
            $table->string('symptom', 256);
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('delete_status')->default(0);
            $table->timestamps();
        });

        // hospital_casualty
        Schema::create('hospital_casualty', function (Blueprint $table) {
            $table->id();
            $table->string('casualty', 256);
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('delete_status')->default(0);
            $table->timestamps();
        });

        // hospital_tpa
        Schema::create('hospital_tpa', function (Blueprint $table) {
            $table->id();
            $table->string('tpa', 256);
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('delete_status')->default(0);
            $table->timestamps();
        });
        
        // hospital_configurations
        Schema::create('hospital_configurations', function (Blueprint $table) {
             $table->id();
             $table->tinyInteger('enable_pharmacy_status')->default(0);
             $table->tinyInteger('enable_pathology_status')->default(0);
             $table->tinyInteger('enable_radiology_status')->default(0);
             $table->tinyInteger('enable_inventory_status')->default(0);
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
        Schema::dropIfExists('hospital_configurations');
        Schema::dropIfExists('hospital_tpa');
        Schema::dropIfExists('hospital_casualty');
        Schema::dropIfExists('hospital_symptom_type');
        Schema::dropIfExists('hospital_frequency');
        Schema::dropIfExists('hospital_units');
        Schema::dropIfExists('hospital_settings_notifications');
        Schema::dropIfExists('hospital_settings_site_logos');
        Schema::dropIfExists('hospital_settings_general_infos');
        Schema::dropIfExists('hospital_hospital');
    }
};
