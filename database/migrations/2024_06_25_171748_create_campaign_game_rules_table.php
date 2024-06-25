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
        Schema::create('campaign_game_rules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('rule_description');
            $table->uuid('game_id');
            $table->foreign('game_id')->references('id')->on('games')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_game_rules');
    }
};
