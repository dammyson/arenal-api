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
        Schema::table('spin_the_wheel_sectors', function (Blueprint $table) {
            $table->dropColumn("spin_the_wheels_id");
            $table->uuid("spin_the_wheel_id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spin_the_wheel_sectors', function (Blueprint $table) {
            $table->uuid("spin_the_wheels_id");
            $table->dropColumn("spin_the_wheel_id");
        });
    }
};
