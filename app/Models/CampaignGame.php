<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;


class CampaignGame extends Pivot
{
    use HasFactory, HasUuids;

    protected $fillable = ['campaign_id', 'game_id', 'details'];
    
    protected $table = 'campaign_games';

    public function campaign() {
        return $this->belongsTo(Campaign::class);
    }

    public function game() {
        return $this->belongsTo(Game::class);
    }

    
}
