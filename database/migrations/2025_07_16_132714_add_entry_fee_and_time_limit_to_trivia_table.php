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
        Schema::table('trivia', function (Blueprint $table) {
            if (!Schema::hasColumn('trivia', 'time_limit')) {
                $table->integer('time_limit')->nullable();
            }

            if (!Schema::hasColumn('trivia', 'entry_fee')) {
                $table->integer('entry_fee')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trivia', function (Blueprint $table) {
            if (Schema::hasColumn('trivia', 'time_limit')) {
                $table->dropColumn('time_limit');
            }

            if (Schema::hasColumn('trivia', 'entry_fee')) {
                $table->dropColumn('entry_fee');
            }
        });
    }
};
