<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class CampaignGamePlay extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['campaign_id', 'game_id', 'audience_id', 'played_at', 'score', 'brand_id'];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function audience()
    {
        return $this->belongsTo(Audience::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}