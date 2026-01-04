<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Campaign extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'type',
        'title',
        'image_url',
        'created_by',
        'client_id',
        'brand_id',
        'company_id',
        'category_id',
        'start_date',
        'end_date',
        'status',
        'daily_ads_budget',
        'total_ads_budget',
        'total_rewards_budget',
        'overall_campaign_budget',
        'daily_start',
        'daily_stop',
        'vendor_id',
        'is_arena',
        'max_participants'
    ];

    public function games()
    {
        return $this->belongsToMany(Game::class, 'campaign_games')
            ->using(CampaignGame::class)
            ->withPivot('details')
            ->withTimestamps();
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function participants()
    {
        return $this->belongsToMany(Audience::class, 'campaign_audience')
            ->withTimestamps();
    }
}
