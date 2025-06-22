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
        if (!Schema::hasColumn('trivia_questions', 'trivia_id')) {
            Schema::table('trivia_questions', function (Blueprint $table) {
                $table->uuid('trivia_id')->nullable(); // Optional: make nullable if needed
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('trivia_questions', 'trivia_id')) {
            Schema::table('trivia_questions', function (Blueprint $table) {
                $table->dropColumn('trivia_id');
            });
        }
    }
};
