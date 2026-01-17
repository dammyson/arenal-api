<?php

namespace App\Services\CampaignGame;

use App\Models\CampaignGame;
use App\Services\BaseServiceInterface;
use App\Http\Requests\Campaign\StoreCampaignGameRequest;
use App\Models\Campaign;

class StoreCampaignGame implements BaseServiceInterface{
    protected $request;
    protected $campaignId;

    public function __construct(StoreCampaignGameRequest $request, $campaignId)
    {
        $this->request = $request;   
        $this->campaignId = $campaignId;   
    }

    public function run() {
       
        $campaign = Campaign::findOrFail($this->campaignId);
       
        $campaign->games()->syncWithoutDetaching([
            $this->request['game_id'] => [
                'details' => $this->request['details'],
            ],

        ]);

        return true;
    
    }
}