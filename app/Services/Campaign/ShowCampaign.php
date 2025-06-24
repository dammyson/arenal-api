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
        
       return CampaignGame::where("campaign_id", $this->campaignId);
    //    return Campaign::where("id", $this->campaignId)->with('games');
    }
}