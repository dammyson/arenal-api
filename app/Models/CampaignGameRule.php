<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignGameRule extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['rule_description', 'campaign_id', 'game_id'];
    
    public function Game() {
        return $this->belongsTo(Game::class);
    }
}
