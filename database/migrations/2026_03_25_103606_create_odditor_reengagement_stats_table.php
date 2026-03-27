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
        Schema::create('odditor_reengagement_stats', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('email');
            $table->boolean('abandoned_then_returned')->nullable();      
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('odditor_reengagement_stats');
    }
};
