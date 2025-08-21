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
        Schema::create('audience_live_streaks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('audience_id');
            $table->uuid('live_id');
            $table->integer('streak_count')->default(0);
            $table->date('last_joined')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audience_live_streaks');
    }
};
