<?php

namespace App\Http\Controllers;

use App\Models\CampaignGamePlay;
use App\Services\CampaignGamePlay\CampaignGamePlayService;

class CampaignGamePlayController extends BaseController
{
    protected $campaignGamePlayService;

    public function __construct()
    {
        $this->campaignGamePlayService = new CampaignGamePlayService();
    }


    public function show($campaignId, $gameId)
    {
        try {
            $data =  $this->campaignGamePlayService->show($campaignId, $gameId);
    
        } catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "user score updated succcessfully", 201);
    
    }


}
