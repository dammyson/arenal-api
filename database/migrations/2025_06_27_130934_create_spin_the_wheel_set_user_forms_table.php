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
        Schema::create('spin_the_wheel_set_user_forms', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('spin_the_wheel_id');
            $table->boolean('is_user_name')->default(false);
            $table->boolean('is_user_email')->default(false);
            $table->boolean('is_phone_number')->default(false);
            $table->boolean('is_marked_required')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spin_the_wheel_set_user_forms');
    }
};
