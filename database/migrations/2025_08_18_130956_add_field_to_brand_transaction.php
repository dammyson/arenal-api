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
        Schema::table('brand_transactions', function (Blueprint $table) {
             // Make status nullable
            $table->enum('status', ['success', 'pending', 'failed'])
                  ->nullable()
                  ->change();

            // Make amount nullable
            $table->integer('amount')
                  ->nullable()
                  ->change();

            // Add new nullable payment_reference column
            $table->string('payment_reference')
                  ->nullable()
                  ->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('brand_transactions', function (Blueprint $table) {
            $table->enum('status', ['success', 'pending', 'failed'])
                  ->nullable(false)
                  ->change();

            // Revert amount to NOT NULL
            $table->integer('amount')
                  ->nullable(false)
                  ->change();

            // Drop payment_reference column
            $table->dropColumn('payment_reference');
        });
    }
};
