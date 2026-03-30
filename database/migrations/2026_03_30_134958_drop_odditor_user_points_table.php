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
        Schema::dropIfExists('odditor_users_points');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('odditor_users_points', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('full_name');
            $table->string('email');
            $table->string('phone_no');
            $table->string('brand_id');
            $table->string('campaign_id');
            $table->integer('points')->default(0);
            $table->enum("status", ['completed', "in_progress", "abandoned"]);
            $table->enum('device_type', ["mobile", "desktop", "tablet"]);
            $table->string('location');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
        });
    }
};
