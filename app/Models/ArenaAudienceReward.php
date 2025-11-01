<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArenaAudienceReward extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['game_id', 'audience_id', 'prize_name', 'prize_code', 'is_redeemed'];

    public function audience() {
        return $this->belongsTo(Audience::class);
    }

    public function game() {
        return $this->belongsTo(Game::class);
    }
}
