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
        Schema::create('spin_the_wheel_buttons', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('spin_the_wheel_id');
            $table->string('color')->nullable();
            $table->boolean('is_solid')->default(true);
            $table->string('border_radius')->nullable();
            $table->boolean('button_3d_styles')->default(true);
            $table->string('text')->nullable();
            $table->string('custom_button_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spin_the_wheel_buttons');
    }
};
