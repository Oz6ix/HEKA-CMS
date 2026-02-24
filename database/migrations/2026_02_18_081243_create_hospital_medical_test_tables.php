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
        // hospital_settings_pathology_category
        Schema::create('hospital_settings_pathology_category', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id');
            $table->string('name', 256);
            $table->longText('description')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('delete_status')->default(0);
            $table->timestamps();
        });

        // hospital_settings_pathology
        Schema::create('hospital_settings_pathology', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('pathology_category_id');
            $table->string('test', 500);
            $table->string('code', 256);
            $table->integer('report_days');
            $table->decimal('charge', 10, 0);
            $table->longText('note')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('delete_status')->default(0);
            $table->timestamps();
        });
        
        // hospital_settings_pathology_parameters
        Schema::create('hospital_settings_pathology_parameters', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('pathology_id');
            $table->string('parameter_name', 256);
            $table->string('range', 256);
            $table->string('unit', 256);
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('delete_status')->default(0);
            $table->timestamps();
        });
        
        // hospital_settings_radiology_category
        Schema::create('hospital_settings_radiology_category', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id');
            $table->string('name', 256);
            $table->longText('description')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('delete_status')->default(0);
            $table->timestamps();
        });
        
        // hospital_settings_radiology
        Schema::create('hospital_settings_radiology', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('radiology_category_id');
            $table->string('test', 500);
            $table->string('code', 256);
            $table->integer('report_days');
            $table->decimal('charge', 10, 0);
            $table->longText('note')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('delete_status')->default(0);
            $table->timestamps();
        });
        
        // hospital_settings_radiology_parameters
        Schema::create('hospital_settings_radiology_parameters', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('radiology_id');
            $table->string('parameter_name', 256);
            $table->string('range', 256);
            $table->string('unit', 256);
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('delete_status')->default(0);
            $table->timestamps();
        });
        
        // hospital_centers
        Schema::create('hospital_centers', function (Blueprint $table) {
             $table->id();
             $table->string('center', 200);
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
        Schema::dropIfExists('hospital_centers');
        Schema::dropIfExists('hospital_settings_radiology_parameters');
        Schema::dropIfExists('hospital_settings_radiology');
        Schema::dropIfExists('hospital_settings_radiology_category');
        Schema::dropIfExists('hospital_settings_pathology_parameters');
        Schema::dropIfExists('hospital_settings_pathology');
        Schema::dropIfExists('hospital_settings_pathology_category');
    }
};
