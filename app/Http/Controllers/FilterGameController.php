<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\CampaignGame;
use App\Http\Controllers\Controller;
use App\Http\Requests\Search\FilterGameRequest;
use App\Services\CampaignGame\FilterCampaignGame;

class FilterGameController extends BaseController
{
    public function filter(FilterGameRequest $request) {
       
        try {
            $data = (new FilterCampaignGame($request['type']))->run();

        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "Game retrieved succcessfully");
    
    }
}


