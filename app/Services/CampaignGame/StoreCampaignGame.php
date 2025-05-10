<?php

namespace App\Services\CampaignGame;

use App\Models\CampaignGame;
use App\Services\BaseServiceInterface;
use App\Http\Requests\Campaign\StoreCampaignGameRequest;

class StoreCampaignGame implements BaseServiceInterface{
    protected $request;
    protected $campaignId;

    public function __construct(StoreCampaignGameRequest $request, $campaignId)
    {
        $this->request = $request;   
        $this->campaignId = $campaignId;   
    }

    public function run() {
       return CampaignGame::updateOrCreate([
            'campaign_id' => $this->campaignId,
        ], [
            'game_id' => $this->request['game_id'],
            'details' => $this->request['details']
        ]);
    
    }
}