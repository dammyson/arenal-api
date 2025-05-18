<?php

namespace App\Http\Controllers;

use App\Models\CampaignGame;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Services\Campaign\IndexCampaign;
use App\Services\CampaignGame\ShowCampaignGame;
use App\Services\CampaignGame\IndexCampaignGame;
use App\Services\CampaignGame\StoreCampaignGame;
use App\Http\Requests\Campaign\StoreCampaignGameRequest;
use App\Services\CampaignGame\IndexFavoriteCampaignGame;

class CampaignGameController extends BaseController
{

    public function storeCampaignGame(StoreCampaignGameRequest $request, $campaignId) {

        try {         
            Gate::authorize('is-audience');
            $data = (new StoreCampaignGame($request, $campaignId))->run();

        } catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "Campaign created succcessfully", 201);
   
    }

    public function indexCampaignGame() {
        try {
            Gate::authorize('is-audience');
            $data = (new IndexCampaignGame())->run();

        } catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "Campaign Games retrieved succcessfully", 201);
   
    
    }

    public function showCampaignGame($campaign_id, $game_id) {
        try {
            Gate::authorize('is-audience');
            $data = (new ShowCampaignGame($campaign_id, $game_id))->run();

        }   catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "Campaign Game retrieved succcessfully", 200);
   
    }

    public function indexFavorite(Request $request)
    {   
        try {
            Gate::authorize('is-audience');
            $data = (new IndexFavoriteCampaignGame($request))->run();
    
        }   catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "Campaign Game retrieved succcessfully", 200);
   
    }

    
}
