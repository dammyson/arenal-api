<?php

namespace App\Http\Controllers;

use App\Models\CampaignGame;
use Illuminate\Http\Request;

class CampaignGameController extends Controller
{
    //

    public function storeCampaignGame(Request $request, $campaign_id) {
        $validated = $request->validate([
            'game_id' => 'sometimes',
            'details' => 'sometimes|string'
        ]);

        try {
            
            $campGame = CampaignGame::create([
                'campaign_id' => $campaign_id,
                'game_id' => $validated['game_id'],
                'details' => $validated['details']
            ]);

        } catch (\Throwable $throwable) {
            
            report($throwable);
            return response()->json([
                'error' => true,
                'message' => "something wrong happened",
                'throwable' => $throwable->getMessage()
            ], 500);
        }

        return response()->json([
            'error' => false,
            'message' => 'campaign Game created successfully',
            $campGame
        ], 201);
    }

    public function indexCampaignGame() {
        try {
            $campGame = CampaignGame::with('game');

        } catch (\Throwable $th) {
            report($th);
            return response()->json([
                'error' => false,
                'message'=> $th->getMessage(),
            ], 500);

        }

        return response()->json([
            'error' => true,
            'message'=> "campaign games",
            $campGame 
        ], 200);
    }

    public function showCampaignGame($campaign_id, $game_id) {
        try {
            $campGame = CampaignGame::where('campaign_id', $campaign_id)
                ->where('game_id', $game_id)
                ->with('game')
                ->get();

        } catch (\Throwable $th) {
            report($th);
            return response()->json([
                'error' => false,
                'message'=> $th->getMessage(),
            ], 500);

        }

        return response()->json([
            'error' => true,
            'message'=> "campaign games",
            $campGame 
        ], 200);
    }
}
