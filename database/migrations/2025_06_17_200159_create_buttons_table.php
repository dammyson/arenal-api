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
        
        Schema::create('buttons', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('spin_the_wheel_sector_id');
            $table->string('button_color')->nullable();
            $table->string('button_solid_style')->nullable();
            $table->string('button_outline_style')->nullable();
            $table->string('button_3d_styles')->nullable();
            $table->string('button_custom_png')->nullable();
            $table->string('custom_png')->nullable();
            $table->boolean('has_custom_png')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buttons');
    }
};
