<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

use Illuminate\Database\Eloquent\Model;

class CampaignGame extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['campaign_id', 'game_id', 'details'];

    public function campaign() {
        return $this->belongsTo(Campaign::class);
    }

    public function game() {
        return $this->belongsTo(Game::class);
    }

    
}
