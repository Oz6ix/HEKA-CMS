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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 255)->nullable();
            $table->string('phone_alternative', 255)->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->integer('staff_id')->nullable();
            $table->tinyInteger('permission_status')->default(0);
            $table->tinyInteger('reset_pwd_status')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('delete_status')->default(0);
        });

        Schema::create('hospital_log', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 50)->nullable();
            $table->integer('admin_id')->default(0);
            $table->integer('record_id')->nullable();
            $table->string('action', 255)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'phone_alternative',
                'profile_photo_path',
                'staff_id',
                'permission_status',
                'reset_pwd_status',
                'status',
                'delete_status'
            ]);
        });

        Schema::dropIfExists('hospital_log');
    }
};
