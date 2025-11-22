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
        Schema::create('audience_branch', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('audience_id')->constrained('audiences')->onDelete('cascade');
            $table->foreignUuid('brand_id')->constrained('brands')->onDelete('cascade');
            $table->foreignUuid('branch_id')->constrained('branches')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['audience_id', 'brand_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audience_branches');
    }
};
