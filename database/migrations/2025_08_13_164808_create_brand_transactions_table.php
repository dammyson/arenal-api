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
        Schema::create('brand_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('audience_id');
            $table->uuid('wallet_id');
            $table->uuid('brand_id');
            $table->string('payment_channel');            
            $table->string('payment_channel_description');            
            $table->enum('status', ['success', 'pending', 'failed']);
            $table->boolean('is_credit');
            $table->string('sender_name');
            $table->integer('amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brand_transactions');
    }
};
