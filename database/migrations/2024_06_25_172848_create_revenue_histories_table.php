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
        Schema::create('revenue_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('wallet_id')->index();
            $table->enum('category', ['subscription', 'ads']);
            $table->uuid('channel_id')->index();
            $table->decimal('amount', 8, 2);
            $table->uuid('campaign_id');
            $table->uuid('activity_id')->index()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revenue_histories');
    }
};
