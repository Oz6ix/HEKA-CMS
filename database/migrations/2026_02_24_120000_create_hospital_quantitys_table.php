<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('hospital_quantitys')) {
            Schema::create('hospital_quantitys', function (Blueprint $table) {
                $table->id();
                $table->string('quantity');
                $table->boolean('status')->default(1);
                $table->boolean('delete_status')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('hospital_quantitys');
    }
};
