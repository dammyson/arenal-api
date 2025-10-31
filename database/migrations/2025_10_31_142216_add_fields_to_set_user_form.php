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
        Schema::table('spin_the_wheel_set_user_forms', function (Blueprint $table) {
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->string('text_style')->nullable();
            $table->string('show_before_spin')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spin_the_wheel_set_user_forms', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->dropColumn('description');
            $table->dropColumn('text_style');
            $table->dropColumn('show_before_spin');
        });
    }
};
