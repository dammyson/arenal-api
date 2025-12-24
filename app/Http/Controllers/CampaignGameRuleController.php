<?php

namespace App\Http\Controllers;

use App\Http\Requests\Campaign\CampaignGameRuleRequest;
use App\Models\Game;
use Illuminate\Http\Request;
use App\Models\CampaignGameRule;
use App\Services\CampaignGameRule\ShowCampaignGameRuleService;
use App\Services\CampaignGameRule\StoreCampaignGameRuleService;

class CampaignGameRuleController extends BaseController
{
    
    public function store(CampaignGameRuleRequest $request, $campaignId, $gameId) {
        try {

            $data = (new StoreCampaignGameRuleService($request, $campaignId, $gameId))->run();

        } catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "Campaign Games rules created succcessfully", 201);
   
    

    }

  
}
