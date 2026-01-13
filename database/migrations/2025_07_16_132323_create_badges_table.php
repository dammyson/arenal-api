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
            Schema::create('badges', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('name');
                $table->string('image_url'); 
                $table->uuid('user_id');
                $table->uuid('brand_id')->nullable();
                $table->boolean('is_arena')->default(false);
                $table->integer('points');
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       
        Schema::dropIfExists('badges');
        
    }
};
