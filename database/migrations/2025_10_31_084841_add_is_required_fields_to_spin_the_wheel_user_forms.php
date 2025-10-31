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
        Schema::table('spin_the_wheel_user_forms', function (Blueprint $table) {
            $table->boolean('is_user_name')->default(false);
            $table->boolean('is_user_email')->default(false);
            $table->boolean('is_phone_number')->default(false);
            $table->boolean('show_from_before_spin')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spin_the_wheel_user_forms', function (Blueprint $table) {
            $table->dropColumn('is_user_name');
            $table->dropColumn('is_user_email');
            $table->dropColumn('is_phone_number');
            $table->dropColumn('show_from_before_spin');
        });
    }
};
