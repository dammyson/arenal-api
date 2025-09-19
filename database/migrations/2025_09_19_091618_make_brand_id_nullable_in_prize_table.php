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
        Schema::table('prizes', function (Blueprint $table) {
            $table->uuid('campaign_id')->nullable()->change();
            $table->uuid('game_id')->nullable()->change();
            $table->uuid('brand_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prizes', function (Blueprint $table) {
            $table->uuid('campaign_id')->nullable(false)->change();
            $table->uuid('game_id')->nullable(false)->change();
            $table->uuid('brand_id')->nullable(false)->change();
        });
    }
};
