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
        Schema::create('trial_records', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('audience_id');
            $table->uuid('spin_the_wheel_participation_details_id');
            $table->date('trial_date');
            $table->integer('trial_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trial_records');
    }
};
