<?php
namespace App\Services\Utility;

use App\Models\CampaignGamePlay;

class LeaderboardService
{

    public function storeLeaderboard($audienceId, $campaignId, $gameId, $brandId, $isArena, $points) {
    
        // If no record exists, create a new one
        $campaignGamePlay = CampaignGamePlay::create([
            'audience_id' => $audienceId,
            'is_arena'=> $isArena,
            'campaign_id' => $campaignId,
            'game_id' => $gameId,
            'brand_id' => $brandId,
            'score' => $points,
            'played_at' => now()
        ]);

        return $campaignGamePlay;
               
            
    }
}