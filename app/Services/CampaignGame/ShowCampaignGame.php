<?php

namespace App\Services\CampaignGame;

use App\Models\Campaign;
use App\Models\CampaignGame;
use App\Services\BaseServiceInterface;
use App\Http\Requests\Campaign\StoreCampaignRequest;
use App\Http\Requests\Campaign\StoreCampaignGameRequest;

class ShowCampaignGame implements BaseServiceInterface{
    protected $gameId;
    protected $campaignId;

    public function __construct( $campaignId, $gameId)
    {
        $this->gameId = $gameId;   
        $this->campaignId = $campaignId;   
    }

    public function run() {
        $campaigns =  CampaignGame::where('campaign_id', $this->campaignId)
            ->where('game_id', $this->gameId)
            ->whereHas('game')
            ->with('campaign', 'game.rules', 'game.trivias')
            ->firstOrFail();
        
        
        return $campaigns;
    
    }
}