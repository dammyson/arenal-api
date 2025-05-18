<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CampaignGamePlay;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\GamePlayRequest;
use App\Services\CampaignGamePlay\CampaignGamePlayService;
use App\Http\Requests\Campaign\UpdateCampaignGamePlayRequest;

class CampaignGamePlayController extends BaseController
{
    protected $campaignGamePlayService;

    public function __construct()
    {
        $this->campaignGamePlayService = new CampaignGamePlayService();
    }
    
    public function storeCampaignGamePlay(GamePlayRequest $request, $campaignId, $gameId) {
        try {
            Gate::authorize('is-audience');
            $data =  $this->campaignGamePlayService->storeCampaignGamePlay($request, $campaignId, $gameId);
    
        } catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "user score updated succcessfully", 201);
    
    }

    public function index()
    {
        try {
            Gate::authorize('is-audience');
            return response()->json(CampaignGamePlay::with('game', 'user', 'campaign')->get());

        } catch (\Throwable $th) {
            return response()->json([
                'error' => true,
                'message' => $th->getMessage()
            ]);
        }
    }



    public function show($campaignId, $gameId)
    {
        try {
            Gate::authorize('is-audience');
            $data =  $this->campaignGamePlayService->show($campaignId, $gameId);
    
        } catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "user score updated succcessfully", 201);
    
    }

    public function update(UpdateCampaignGamePlayRequest $request, $campaignId, $gameId)
    {
        try {
            Gate::authorize('is-audience');
            $data =  $this->campaignGamePlayService->update($request, $campaignId, $gameId);
    
        } catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "campaign game deleted succcessfully", 204);
     
    }

    public function destroy($campaignId, $gameId)
    {
        try {
            Gate::authorize('is-user');
            $data =  $this->campaignGamePlayService->destroy($campaignId, $gameId);
    
        } catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "campaign game deleted succcessfully", 204);
    
    }

}
