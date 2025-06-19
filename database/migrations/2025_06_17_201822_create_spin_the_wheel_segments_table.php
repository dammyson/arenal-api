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
         
    
        Schema::create('spin_the_wheel_segments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('spin_the_wheel_id');
            $table->string('label_text')->nullable();
            $table->string('label_color')->nullable();
            $table->string('background_color')->nullable();
            $table->string('icon')->nullable();
            $table->decimal('probability')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spin_the_wheel_sector_segments');
    }
};
