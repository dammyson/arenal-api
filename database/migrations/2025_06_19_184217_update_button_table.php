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
        Schema::table('buttons', function (Blueprint $table) {
            $table->dropColumn('spin_the_wheel_sector_id');
            $table->uuid('spin_the_wheel_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buttons', function (Blueprint $table) {
            $table->dropColumn('spin_the_wheel_id');
            $table->uuid('spin_the_wheel_sector_id');
        });
    }
};
