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
        Schema::create('rewards', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('campaign_id');
            $table->string('name');
            $table->string('type'); // cash | airtime | item
            $table->integer('points_required');
            $table->decimal('stock_total', 15, 2)->nullable(); // nullable (for unlimited)
            $table->decimal('stock_remaining', 15, 2)->nullable(); // nullable
            $table->boolean('is_active')->default(true);
            $table->uuid('rewardable_id');
            $table->string('rewardable_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rewards');
    }
};
