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
        Schema::table('spin_the_wheel_backgrounds', function (Blueprint $table) {
            $table->dropColumn('backgound_gradient')->nullable();
            $table->string('background_gradient')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spin_the_wheel_backgrounds', function (Blueprint $table) {
            $table->string('backgound_gradient')->nullable();
            $table->dropColumn('background_gradient')->nullable();
        });
    }
};
