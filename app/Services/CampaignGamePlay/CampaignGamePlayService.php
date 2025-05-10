<?php

namespace App\Services\CampaignGamePlay;

use Carbon\Carbon;
use App\Models\Campaign;
use App\Models\CampaignGame;
use App\Models\CampaignGamePlay;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\GamePlayRequest;
use App\Http\Requests\Campaign\StoreCampaignGameRequest;
use App\Http\Requests\Campaign\UpdateCampaignGamePlayRequest;

class CampaignGamePlayService {

    public function __construct()
    {
          
    }

    public function storeCampaignGamePlay(GamePlayRequest $request, $campaignId, $gameId) {
       
        $userId = $request->user()->id;
        $score = (int) $request->input('score');
        // $played_at = $request->input('played_at');

        // Start a transaction
        DB::beginTransaction();

        // Fetch the record and lock it for update
        $campaignGamePlay = CampaignGamePlay::where('user_id', $userId)
            ->where('campaign_id', $campaignId)
            ->where('game_id', $gameId)
            ->lockForUpdate()  // Apply pessimistic locking
            ->first();

        if (!$campaignGamePlay) {
            // If no record exists, create a new one
            $campaignGamePlay = CampaignGamePlay::create([
                'user_id' => $userId,
                'campaign_id' => $campaignId,
                'game_id' => $gameId,
                'score' => $score,
                'played_at' => now()
            ]);
        } else {
            // If record exists, increment score and update played_at
            $campaignGamePlay->score += $score;
            $campaignGamePlay->played_at = now();
            $campaignGamePlay->save();
        }

        // Commit the transaction after updates
        DB::commit();
        
        return $campaignGamePlay;
       
    
    }


    public function show($campaignId, $gameId)
    {        
        return CampaignGamePlay::where('campaign_id', $campaignId)
            ->where('game_id', $gameId)
            ->with('game', 'user', 'campaign')->get();

       
    }
    
    public function update(UpdateCampaignGamePlayRequest $request, $campaignId, $gameId)
    {
        $user = $request->user();
        $userCampaignGamePlay = CampaignGamePlay::where('campaign_id', $campaignId)
            ->where('game_id', $gameId)
            ->where('user_id', $user->id)
            ->first();

        return  $userCampaignGamePlay->update([
            ...$request->validated(),
            'campaign_id' => $campaignId,
            'game_id' => $gameId,
            'user_id' => $user->id
        ]);

    }

    public function destroy($campaignId, $gameId)
    {        
        $campaignGamePlay = CampaignGamePlay::where('campaign_id', $campaignId)
            ->where('game_id', $gameId)->first();
        
        $campaignGamePlay->delete();
        return 'deleted';

       
    }
    
    
    
    
}