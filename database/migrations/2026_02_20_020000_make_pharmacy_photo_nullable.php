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
        Schema::table('hospital_settings_pharmacy', function (Blueprint $table) {
            $table->string('photo', 200)->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hospital_settings_pharmacy', function (Blueprint $table) {
            $table->string('photo', 200)->nullable(false)->change();
        });
    }
};
