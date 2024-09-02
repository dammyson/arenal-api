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
        Schema::dropIfExists('transactions');  // Drop the 'transactions' table
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optionally, you can recreate the table here if needed
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_credit');
            // Add any other columns that existed before
            $table->timestamps();
        });
    }
};
