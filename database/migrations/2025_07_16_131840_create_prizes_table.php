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
        if (!Schema::hasTable('prizes')) {
            Schema::create('prizes', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('name');
                $table->string('description');
                $table->uuid('campaign_id');
                $table->uuid('game_id');
                $table->uuid('brand_id');
                $table->timestamps();
            });
        }

       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('prizes')) {
            Schema::dropIfExists('prizes');
        }
    }
};
