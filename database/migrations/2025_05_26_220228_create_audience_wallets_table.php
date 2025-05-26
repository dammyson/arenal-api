<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.  docker-compose run --rm artisan migrate:refresh --path=/database/migrations/2025_05_26_220228_create_audience_wallets_table.php
     */
    public function up(): void
    {
        Schema::create('audience_wallets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('audience_id')->nullable();
            $table->decimal('balance', 8, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audience_wallets');
    }
};
