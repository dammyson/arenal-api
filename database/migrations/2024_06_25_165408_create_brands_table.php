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
        Schema::create('brands', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('image_url')->nullable();
            $table->string('industry_code');
            $table->string('sub_industry_code');
            $table->string('slug');
            $table->string('description')->nullable();
            $table->string('closes_on')->nullable();
            $table->boolean('is_arena')->default(false);
            $table->decimal('daily_bonus', 8, 2)->default(0);
            $table->integer('high_score_bonus')->default(0);

            $table->uuid('created_by');
            $table->foreign('created_by')->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
                
            $table->uuid('client_id');
            $table->foreign('client_id')->references('id')->on('clients')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
