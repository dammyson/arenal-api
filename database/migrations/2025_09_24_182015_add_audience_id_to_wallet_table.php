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
        if (!Schema::hasColumn('wallets', 'audience_id')) {
            Schema::table('wallets', function (Blueprint $table) {
                $table->uuid('audience_id')->nullable()->after('id');
                $table->foreign('audience_id')->references('id')->on('audiences')
                    ->onDelete('set null')
                    ->onUpdate('cascade');
            });
            
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      
        if (Schema::hasColumn('wallets', 'audience_id')) {
            Schema::table('wallets', function (Blueprint $table) {
                $table->dropForeign(['audience_id']);
                $table->dropColumn('audience_id');  
            });
        }
    }
};
