<?php

namespace App\Http\Controllers;

use App\Http\Requests\Campaign\StoreCampaignGameRequest;
use App\Models\CampaignGame;
use Illuminate\Http\Request;

class CampaignGameController extends Controller
{

    public function storeCampaignGame(StoreCampaignGameRequest $request, $campaign_id) {
        

        try {
            $user = $request->user();

            if ($user->is_audience) {
                return response()->json([
                    'error' => true, 
                    'message' => "unauthorized"
                ], 401);
            }

            $campGame = CampaignGame::updateOrCreate([
                'campaign_id' => $campaign_id,
            ], [
                'game_id' => $request['game_id'],
                'details' => $request['details']
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
            'campaign_game' => $campGame
        ], 201);
    }

    public function indexCampaignGame() {
        try {
            $campGame = CampaignGame::with('game')->get();

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
            'campaign_game' => $campGame 
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

    public function indexFavorite(Request $request)
    {   
        try {
            $audience = $request->user();

            $favoriteCampaignGames = CampaignGame::whereHas('game', function($query) use($audience){
                $query->where('user_id', $audience->id)
                ->where('is_favorite', true);
            })->with('game')->get();
    
            return response()->json([
                'error' => false,
                'favorite_games' => $favoriteCampaignGames
            ], 200);

            $response = $favoriteCampaignGames->map(function($campaignGame) {
                return [
                    'campaign_id' => $campaignGame->campaign_id,
                    'game_id' => $campaignGame->game_id,
                    'name' => $campaignGame->game->name,
                    'type' => $campaignGame->game->type,
                    'image_url' => $campaignGame->game->image_url,
                    'is_favorite' => $campaignGame->game->is_favorite
                ];
            });

        } catch(\Throwable $throwable) {
            
            report($throwable);
            return  response()->json([
                "error" => "true",
                "message" => $throwable->getMessage()
            ], 500);
        }

        return response()->json([
            "error" => "false", 
            "favorite_games" => $response
        ], 200);
    }

    
}
