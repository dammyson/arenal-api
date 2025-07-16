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

        

        if (!Schema::hasTable('brand_details')) {            
            Schema::create('brand_details', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('detail');
                $table->uuid('brand_id');
                $table->uuid('user_id');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {       
        if (!Schema::hasTable('brand_details')) {
            Schema::dropIfExists('brand_details');
        }
    }
};
