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
        if (!Schema::hasColumn('campaign_leaderboards', 'brand_id')) {
            Schema::table('campaign_leaderboards', function (Blueprint $table) {
                $table->uuid('brand_id')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    { 
        if (Schema::hasColumn('campaign_leaderboards', 'brand_id')) {
            Schema::table('campaign_leaderboards', function (Blueprint $table) {
                $table->dropColumn('brand_id');
            });
        }
    }
};
