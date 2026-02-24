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
        Schema::table('hospital_appointment_basics_details', function (Blueprint $table) {
            $table->string('spo2', 256)->nullable()->after('temperature_unit');
            $table->string('rbs', 256)->nullable()->after('spo2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hospital_appointment_basics_details', function (Blueprint $table) {
            $table->dropColumn('spo2');
            $table->dropColumn('rbs');
        });
    }
};
