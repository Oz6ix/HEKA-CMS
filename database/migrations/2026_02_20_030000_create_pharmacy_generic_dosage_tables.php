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
        // hospital_pharmacy_generic
        Schema::create('hospital_pharmacy_generic', function (Blueprint $table) {
            $table->id();
            $table->string('generic', 256);
            $table->tinyInteger('del_status')->default(0);
            $table->timestamps();
        });

        // hospital_pharmacy_dosage
        Schema::create('hospital_pharmacy_dosage', function (Blueprint $table) {
            $table->id();
            $table->string('dosage', 256);
            $table->tinyInteger('del_status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospital_pharmacy_dosage');
        Schema::dropIfExists('hospital_pharmacy_generic');
    }
};
