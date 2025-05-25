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
        Schema::create('campaign_leaderboards', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('campaign_id');
            $table->uuid('audience_id');
            $table->foreign('audience_id')->references('id')->on('audiences')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->double('play_durations');
            $table->integer('play_points')->default(0);
            $table->integer('referral_points')->default(0);
            $table->integer('total_points')->default(0);
            $table->integer("player_position")->default(0)->change();
            $table->integer("top_players_start")->default(0);
            $table->integer("top_players_end")->default(0);
            $table->integer("top_players_revenue_share_percent")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_leaderboards');
    }
};
