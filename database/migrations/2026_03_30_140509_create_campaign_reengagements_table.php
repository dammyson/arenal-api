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
        Schema::create('campaign_reengagements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('brand_id');
            $table->string('campaign_id');
            $table->string('email');
            $table->boolean('abandoned_then_returned')->nullable();      
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_reengagements');
    }
};
