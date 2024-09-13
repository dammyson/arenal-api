<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CampaignGamePlay;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\GamePlayRequest;
use App\Http\Requests\Campaign\UpdateCampaignGamePlayRequest;

class CampaignGamePlayController extends Controller
{

    public function storeCampaignGamePlay(GamePlayRequest $request, $campaignId, $gameId) {
        try {
            $userId = $request->user()->id;
            $score = (int) $request->input('score');
            $played_at = $request->input('played_at');
    
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
                    'played_at' => $played_at
                ]);
            } else {
                // If record exists, increment score and update played_at
                $campaignGamePlay->score += $score;
                $campaignGamePlay->played_at = $played_at;
                $campaignGamePlay->save();
            }
    
            // Commit the transaction after updates
            DB::commit();
    
        } catch (\Throwable $th) {
            // Rollback transaction in case of any error
            DB::rollBack();
            return response()->json([
                'error' => true,
                'message' => $th->getMessage()
            ], 500);
        }
    
        // Return successful response with updated/created data
        return response()->json([
            'error' => false,
            'campaign_game_play' => $campaignGamePlay
        ], 201);
    }

    public function index()
    {
        try {
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
            $campaignGamePlay = CampaignGamePlay::where('campaign_id', $campaignId)
                ->where('game_id', $gameId)
                ->with('game', 'user', 'campaign')->get();

        }  catch (\Throwable $th) {
            return response()->json([
                'error' => true,
                'message' => $th->getMessage()
            ], 500);
        }

        return response()->json([
            'error' => false,
            'campaign_game_play' => $campaignGamePlay
        ], 200);
    }

    public function update(UpdateCampaignGamePlayRequest $request, $campaignId, $gameId)
    {
        try {
            $user = $request->user();
            $userCampaignGamePlay = CampaignGamePlay::where('campaign_id', $campaignId)
                ->where('game_id', $gameId)
                ->where('user_id', $user->id)
                ->first();

            $userCampaignGamePlay->update([
                ...$request->validated(),
                'campaign_id' => $campaignId,
                'game_id' => $gameId,
                'user_id' => $user->id
            ]);

        }  catch (\Throwable $th) {
            return response()->json([
                'error' => true,
                'message' => $th->getMessage()
            ], 500);
        }

        return response()->json([
           'error' => false,
           'userCampaignGamePlay' => $userCampaignGamePlay
        ], 201);
    }

    public function destroy($campaignId, $gameId)
    {
        try {
            $campaignGamePlay = CampaignGamePlay::where('campaign_id', $campaignId)
                ->where('game_id', $gameId)->first();
            
            $campaignGamePlay->delete();

        }  catch (\Throwable $th) {
            return response()->json([
                'error' => true,
                'message' => $th->getMessage()
            ], 500);
        }

        return response()->json(null, 204);
    }

}
