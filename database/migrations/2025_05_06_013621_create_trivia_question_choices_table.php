<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations. docker-compose run --rm artisan migrate:refresh --path=/database/migrations/2025_05_06_013621_create_trivia_question_choices_table.php
     */
    public function up(): void
    {
        Schema::create('trivia_question_choices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('question_id')->index();
            $table->text('choice');
            $table->boolean('is_correct_choice');
            $table->enum('media_type', ['image', 'audio', 'video'])->nullable();
            $table->string('asset_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trivia_question_choices');
    }
};
