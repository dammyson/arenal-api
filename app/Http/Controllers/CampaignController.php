<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Campaign\StoreCampaignRequest;
use App\Models\Campaign;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function index()
    {
        try {
            $campaigns = Campaign::get();

            if ($campaigns->isEmpty()) {
                return response()->json(['message' => 'No campaigns found.'], 404);
            }

        } catch (\Throwable $th) {
            report($th);
            return response()->json(['error' => true, 'message' => 'Something went wrong'], 500);
        }
        return response()->json(['message' => 'Active campaigns found.', 'campaigns' => $campaigns], 200);
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

            $user = $request->user();

            if ($user->is_audience) {
                return response()->json([
                    'error' => true, 
                    'message' => "unauthorized"
                ], 401);
            }

            $campaign = Campaign::create([...$request->validated(), 'created_by' => $user->id]);

        } catch (\Throwable $th) {
            report($th);
            return response()->json(['error' => true, 'mesage' => $th->getMessage()], 500);
        }
        return response()->json(['error' => false, 'message' => 'Campaigns', 'data' =>  $campaign], 201);
    }


    public function showCampaign($campaign_id)
    {
        try {
            $campaign = Campaign::find($campaign_id);
        } catch (\Throwable $th) {
            report($th);
            return response()->json(['error' => true, 'mesage' => 'something went wrong'], 500);
        }
        return response()->json(['error' => false, 'message' => 'Campaign information', 'data' =>  $campaign], 200);
    }
}
