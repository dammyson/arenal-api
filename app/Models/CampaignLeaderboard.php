<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignLeaderboard extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'campaign_id',
        'audience_id',
        'play_durations',
        'play_points',
        'referral_points',
        'total_points',
        'player_position',
        'top_players_start',
        'top_players_end',
        'top_players_revenue_share_percent'

    ];
    public function audience() {
        return $this->belongsTo(Audience::class);
    }

    public function campaign() {
        return $this->belongsTo(Campaign::class);
    }
}
