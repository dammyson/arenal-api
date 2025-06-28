<?php

namespace App\Services\Campaign;

use App\Models\Campaign;
use App\Services\BaseServiceInterface;
use App\Http\Requests\Campaign\StoreCampaignRequest;
use App\Models\CampaignGame;

class ShowCampaign implements BaseServiceInterface{
    protected $campaignId;

    public function __construct($campaignId)
    {
        $this->campaignId = $campaignId;   
    }

    public function run() {
        
        // return CampaignGame::where("campaign_id", $this->campaignId)->first();
        return CampaignGame::where("campaign_id", $this->campaignId)
            ->whereHas('game') // Just check if game relationship exists
            ->with(['campaign', 'game.rules']) // Eager load both campaign and rules of game
            ->firstOrFail();
    //    return Campaign::where("id", $this->campaignId)->with('games')->first();
    }
}