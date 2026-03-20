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
        Schema::create('odditor_educator_pages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('brand_id');
            $table->string('title');
            $table->string('description');
            $table->integer('audit_count');
            $table->integer('overcharge_count');
            $table->integer('cities_served');
            $table->string('button_header_text');
            $table->string('button_subheader_text');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('odditor_educator_pages');
    }
};
