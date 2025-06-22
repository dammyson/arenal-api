<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
      //   and , 
    public function up(): void
    {
        Schema::table('spin_the_wheels', function (Blueprint $table) {
            $table->dateTimeTz('start_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spin_the_wheel_sectors', function (Blueprint $table) {
            $table->dropColumn('backgound_gradient');
            $table->dropColumn('background_color');
            $table->dropColumn('background_image');
            $table->dropColumn('start_time');
        });
    }
};
