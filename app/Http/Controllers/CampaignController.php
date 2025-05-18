<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Services\Campaign\ShowCampaign;
use App\Services\Campaign\IndexCampaign;
use App\Services\Campaign\StoreCampaign;
use App\Services\CampaignGame\ShowCampaignGame;
use App\Http\Requests\Campaign\StoreCampaignRequest;

class CampaignController extends BaseController
{
    public function index()
    {
        try {
            Gate::authorize('is-audience');

           $data = (new IndexCampaign())->run();

        } catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "Campaign created succcessfully", 201);
   
    
    }

    public function fetchCampaigns($title)
    {
        try {
            Gate::authorize('is-audience');

            $fetchedCampaign = Campaign::where('title', $title)->first();
    
            if (!$fetchedCampaign) {
                return response()->json(['error' => true, 'message' => 'Campaign not found'], 404);
            }
    
            return response()->json(['error' => false, 'data' => $fetchedCampaign->id], 200);
    
        } catch (\Exception $exception) {
            return response()->json(['error' => true, 'message' => $exception->getMessage()], 500);
        }
    }
    


    public function storeCampaign(StoreCampaignRequest $request)
    {
        try {
            Gate::authorize('is-audience');

            $data = (new StoreCampaign($request))->run();

        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "Campaign created succcessfully", 201);
   
    
    }


    public function showCampaign($campaignId)
    {
        try {
            Gate::authorize('is-audience');
            $data = (new ShowCampaign($campaignId))->run();

        } catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "Campaign retrieved succcessfully", 200);
   
    }

    public function startCampaign($campaignId)
    {
        try {
            Gate::authorize('is-audience');

            $campaign = Campaign::find($campaignId);
            $campaign->status = "ACTIVE";
            $campaign->start_date = now();
            $campaign->save();
           
        } catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($campaign, "Campaign started succcessfully", 200);
    }

    public function generateCampaignLink($campaignId, $gameId)
    {
        try {
            Gate::authorize('is-user');

            $campaign = Campaign::find($campaignId);
            $expired = now()->addHour(24);

            $url =  URL::temporarySignedRoute('play.game',  $expired, ['campaign_id'=>  $campaignId, 'game_id' => $gameId]);
dd($url);
            $urlComponents = parse_url($url);
            $front_url = env('FRONT_END_URL', 24) .'?' . $urlComponents['query'];
           
        } catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($front_url, "Campaign updated succcessfully", 200);
   
    }

    public function goToCampaignGame(Request $request) {
        try {
           // Gate::authorize('is-audience');

           $campaignId = $request->query('campaign_id');
           $expires = $request->query('expires');
           $gameId = $request->query('game_id');
           $signature = $request->query('signature');
            
            if (!$request->hasValidSignature()) {
                return response()->json(['status' => false, 'message' => 'Invalid/Expired link, contact admin'], 401);
            }

            $data = (new ShowCampaignGame($campaignId, $gameId))->run();

        }   catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "Campaign Game retrieved succcessfully", 200);
   
    }

}
