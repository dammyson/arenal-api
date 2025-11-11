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
        Schema::table('spin_the_wheel_participation_details', function (Blueprint $table) {
            $table->integer('no_of_free_trials')->default(0)->after('entry_fee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spin_the_wheel_participation_details', function (Blueprint $table) {
            $table->dropColumn('no_of_free_trials');
        });
    }
};
