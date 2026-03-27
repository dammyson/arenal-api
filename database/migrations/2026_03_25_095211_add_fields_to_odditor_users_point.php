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
        Schema::table('odditor_users_points', function (Blueprint $table) {
            $table->enum("status", ['completed', "in_progress", "abandoned"]);
            $table->enum('device_type', ["mobile", "desktop", "tablet"]);
            $table->string('location');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('odditor_users_points', function (Blueprint $table) {
            $table->dropColumn(['status', 'started_at', 'ended_at', 'device_type']);

            
        });
    }
};
