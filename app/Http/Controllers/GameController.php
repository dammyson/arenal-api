<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGameRequest;
use App\Models\CampaignGame;
use PhpParser\Node\Expr\FuncCall;

class GameController extends Controller
{
    public function index() {

        try {
            $games = Game::get();

            if ($games->isEmpty()) {
                return response()->json(['message' => 'No games found.'], 404);
            }
            

        } catch(\Throwable $throwable) {
            
            report($throwable);
            response()->json([
                "error" => "true",
                "message" => $throwable->getMessage()
            ], 500);
        }

        return response()->json(["error" => "false", 
        
        "gamees" => $games], 200);

    }

   
    public function storeGame(StoreGameRequest $request) {
       try {

            $user = $request->user();

            if ($user->is_audience) {
                return response()->json([
                    'error' => true, 
                    'message' => "unauthorized"
                ], 401);
            }
         

            $game = Game::create([
                ...$request->validated(),
                'user_id' => $user->id
            ]);

        } catch (\Throwable $throwable) {

            report($throwable);
            return response()->json([
                'error' => 'true',
                'message' => $throwable->getMessage()
            ], 500);
        }

        return response()->json([
            "error" => "false",         
            "game" => $game
        ], 201);
    }

    public function showGame($gameId) {
        try {
            
            $game = Game::where('id', $gameId)->with('rules')->first();

           if (!$game) {
            return response()->json(['error' => 'false', 'message' => 'no game found'], 404);
           }
        
        } catch (\Throwable $throwable) {

            report($throwable);
            return response()->json([
                'error' => 'true',
                'message' => $throwable->getMessage()
            ], 500);
        }

        return response()->json([
            "error" => "false",
            "game_with_rules" => $game
        ], 200);
    }

    public function updateGame(Request $request, $game_id) {
        $validated = $request->validate([
            'name' => 'sometimes',
            'image_url' => 'sometimes',
            'type' => 'sometimes'
        ]);


        try {

            $user = $request->user();

            if ($user->is_audience) {
                return response()->json([
                    'error' => true, 
                    'message' => "unauthorized"
                ], 401);
            }
            
            $game = Game::find($game_id);

            if (!$game) {
                return response()->json(['error' => 'false', 'message' => 'Game not found'], 404);
            }

            $game->name = $validated['name'] ?? $game->name;
            $game->image_url = $validated['image_url'] ?? $game->image_url;
            $game->type =$validated['type'] ?? $game->type;
            $game->save();

        } catch (\Throwable $throwable) {
            report($throwable);
            return response()->json([
                'error' => 'true',
                'message' => $throwable->getMessage()
            ], 500);

        }

        return response()->json(["error" => "false", 'message' => 'Game updated successfully', $game], 200);
        
    }


    public function filter(Request $request) 
    {
        $validated = $request->validate([
            'type' => 'required|string'
        ]);

        try {

            $categoryName = $validated['type'];
            
            $start_week = Carbon::now()->startOfWeek()->format('Y-m-d');
            $end_week = Carbon::now()->endOfWeek()->format('Y-m-d');
            
    
            if ($categoryName == "all") {
                $games = CampaignGame::whereHas('game', function ($query) use($start_week, $end_week) {
                    $query->whereDate('created_at', '>=', $start_week)
                    ->whereDate('created_at', '<=', $end_week); 
                   
                })->with('game')->get();
                
        
            
            } else {
                
                $games = CampaignGame::whereHas('game', function ($query) use($start_week, $end_week, $categoryName) {
                    $query
                    ->where('type', $categoryName)
                    ->whereDate('created_at', '>=', $start_week)
                    ->whereDate('created_at', '<=', $end_week); 
                   
                })->with('game')->get();
                
            }
        } catch(\Throwable $throwable) {
            
            report($throwable);
            response()->json([
                "error" => "true",
                "message" => $throwable->getMessage()
            ], 500);
        }

        return response()->json(["error" => "false", $games], 200);

    }


    public function toogleFavorite($game_id) {
        try {
            $game = Game::find($game_id);
            $game->is_favorite = !$game->is_favorite;
            $game->save();

        } catch(\Throwable $throwable) {
            
            report($throwable);
            response()->json([
                "error" => "true",
                "message" => $throwable->getMessage()
            ], 500);
        }

        return response()->json([
            "error" => "false", 
            $game
        ], 200);


    }



    public function gamesByType() {
         // Retrieve CampaignGame models and join with games to group by game type
        try{
            $campaignGamesByType = CampaignGame::with('game')
                ->get();

            

        } catch(\Throwable $throwable) {
            report($throwable);
            return response()->json([
                "error" => "true",
                "message" => $throwable->getMessage()
            ], 500);
        }

        return response()->json([
            "error" => "false", 
            "data" => $campaignGamesByType
        ], 200);
    }
    


}
