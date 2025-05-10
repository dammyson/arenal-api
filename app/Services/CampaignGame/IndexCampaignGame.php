<?php

namespace App\Services\CampaignGame;

use App\Models\CampaignGame;
use App\Services\BaseServiceInterface;
use App\Http\Requests\Campaign\StoreCampaignGameRequest;

class IndexCampaignGame implements BaseServiceInterface{

    public function __construct()
    {  
    }

    public function run() {
       return  CampaignGame::with('game')->get();
    
    }
}