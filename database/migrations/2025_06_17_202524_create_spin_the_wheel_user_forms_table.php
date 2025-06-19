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
        Schema::create('spin_the_wheel_user_forms', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('spin_the_wheel_id');
            $table->string('user_name')->nullable();
            $table->string('user_email')->nullable();
            $table->string('phone_number')->nullable();
            $table->boolean('is_marked_required')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spin_the_wheel_sector_user_forms');
    }
};
