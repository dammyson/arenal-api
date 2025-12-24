<?php

namespace App\Services\CampaignGamePlay;

use App\Models\CampaignGamePlay;

class CampaignGamePlayService {

    public function __construct()
    {
          
    }

    public function show($campaignId, $gameId)
    {        
        return CampaignGamePlay::where('campaign_id', $campaignId)
            ->where('game_id', $gameId)
            ->with('game', 'audience', 'campaign')->get();
       
    }
    
    
}