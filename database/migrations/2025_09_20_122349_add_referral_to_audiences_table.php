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
        Schema::table('audiences', function (Blueprint $table) {
            $table->string('referrer_id')->nullable()->after('pin');
            $table->uuid('referred_by')->nullable()->after('referrer_id');
            // include total_referral_point
            $table->integer('total_referral_point')->default(0)->after('referred_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audience', function (Blueprint $table) {
            $table->dropColumn('referral_id');
            $table->dropColumn('referred_by');
            $table->dropColumn('total_referral_point');
        });
    }
};
