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
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('wallet_id')->index();
            $table->string('category');
            $table->uuid('channel_id')->index();
            $table->decimal('amount', 8, 2);
            $table->string("reference")->nullable(); // if category is ad_revenue reference will be ad activity id, if it is a payout or top up, reference will be flutterwave ref
            $table->string("status")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
