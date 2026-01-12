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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type'); // ['ADVERT', 'SURVERY', 'TOPBRAIN']
            $table->string('title');
            $table->time('daily_start')->default('00:00:00');
            $table->time('daily_stop')->default('11:59:59');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('daily_ads_budget', 8, 2)->default(0);
            $table->decimal('total_ads_budget', 8, 2)->default(0);
            $table->decimal('total_rewards_budget', 8, 2)->default(0);
            $table->decimal('overall_campaign_budget', 8, 2)->default(0);
            $table->uuid('created_by');
            $table->string('image_url');
            $table->uuid('category_id')->nullable();
            $table->boolean('is_arena')->default(false);
            $table->unsignedInteger('max_participants')->nullable();
            $table->foreign('created_by')->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->uuid('brand_id');
            $table->foreign('brand_id')->references('id')->on('brands')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->uuid('client_id');
            $table->foreign('client_id')->references('id')->on('clients')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->uuid('company_id');
            $table->foreign('company_id')->references('id')->on('companies')
                ->onDelete('cascade')
                ->onUpdate('cascade');
                
            $table->enum('status', ['CREATED', 'SUBMITTED', 'ACTIVE', 'PAUSED', 'APPROVED', 'REJECTED', 'COMPLETED'])->default('CREATED')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
