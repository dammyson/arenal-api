<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.  docker-compose run --rm artisan migrate:refresh --path=/database/migrations/2024_06_22_125504_create_audiences_table.php
     */
    public function up(): void
    {
        Schema::create('audiences', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('password');
            $table->string('phone_number')->unique()->nullable();
            $table->string('profile_image')->nullable();
            $table->string('pin')->nullable();
            $table->uuid('user_id')->nullable();
            $table->string('referrer_id')->nullable();
            $table->uuid('referred_by')->nullable();
            $table->integer('total_referral_point')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audiences');
    }
};
