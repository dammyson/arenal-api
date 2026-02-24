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
        Schema::create('world_custom_first_pages', function (Blueprint $table) {
            $table->id();
            $table->uuid('brand_id');
            $table->string('header_one');
            $table->string('header_two');
            $table->string('header_two_description');
            $table->string('header_three');
            $table->string('header_three_description');
            $table->string('btn_text');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('world_custom_first_pages');
    }
};
