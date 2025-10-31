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
        Schema::table('spin_the_wheels', function (Blueprint $table) {
            $table->boolean('is_published')->default(true);
            $table->dateTimeTz('publish_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spin_the_wheels', function (Blueprint $table) {
            $table->dropColumn('is_published');
            $table->dropColumn('publish_time');
        });
    }
};
