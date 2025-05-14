<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Campaign\StoreCampaignRequest;
use App\Models\Campaign;
use App\Services\Campaign\IndexCampaign;
use App\Services\Campaign\ShowCampaign;
use App\Services\Campaign\StoreCampaign;
use Illuminate\Http\Request;

class CampaignController extends BaseController
{
    public function index()
    {
        try {
           $data = (new IndexCampaign())->run();

        } catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "Campaign created succcessfully", 201);
   
    
    }

    public function fetchCampaigns($title)
    {
        try {
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
            $data = (new StoreCampaign($request))->run();

        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "Campaign created succcessfully", 201);
   
    
    }


    public function showCampaign($campaignId)
    {
        try {
            $data = (new ShowCampaign($campaignId))->run();

        } catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "Campaign retrieved succcessfully", 200);
   
    }
}
