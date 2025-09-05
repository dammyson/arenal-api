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
        Schema::create('arena_demographies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('audience_id');
            $table->string('favorite_team')->nullable();
            $table->string('favorite_sport')->nullable();
            $table->string('instagram_handle')->nullable();
            $table->string('favorite_music_genre')->nullable();
            $table->string('favorite_food')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arena_demographies');
    }
};
