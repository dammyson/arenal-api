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
      
        Schema::create('spin_the_wheel_sectors', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid("spin_the_wheel_id");
            $table->string('text')->nullable();
            $table->string('color')->nullable();
            $table->string('value')->nullable();
            $table->string('image_url')->nullable();
            $table->uuid('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spin_the_wheel_sectors');
    }
};
