<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.  docker-compose run --rm artisan migrate:refresh --path=/database/migrations/2025_05_06_012220_create_trivia_questions_table.php
     */
    public function up(): void
    {
        Schema::create('trivia_questions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('question');
            $table->boolean('is_general')->default(true);
            $table->decimal('points')->default(1.0);
            $table->decimal('duration')->default(0.0);
            $table->enum('media_type', ['image', 'audio', 'video'])->nullable();
            $table->string('asset_url')->nullable();
            $table->enum('difficulty_level', ['EASY', 'MEDIUM', 'HARD']) ->default('EASY');
            $table->uuid('company_id');
            $table->uuid('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trivia_questions');
    }
};
