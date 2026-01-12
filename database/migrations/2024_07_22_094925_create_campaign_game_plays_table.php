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
        Schema::create('campaign_game_plays', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('campaign_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('game_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('audience_id')->constrained()->onDelete('cascade');
            $table->uuid('brand_id')->nullable();
            $table->integer('score');
            $table->timestamp('played_at');
            $table->boolean('is_arena')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_game_plays');
    }
};
