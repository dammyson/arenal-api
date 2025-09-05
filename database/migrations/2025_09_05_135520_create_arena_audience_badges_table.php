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
        Schema::create('arena_audience_badges', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('arena_badge_id');
            $table->uuid('audience_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arena_audience_badges');
    }
};
