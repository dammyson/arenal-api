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
        Schema::dropIfExists('transaction_histories');  // Drop the 'transactions' table
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('transaction_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('wallet_id');
            $table->foreign('wallet_id')->references('id')->on('wallets')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('receipient_name');
            $table->string('transaction_id');
            $table->integer('amount');
            $table->timestamps();
        });
    }
};
