<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class GameController extends Controller
{
    //
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

        return response()->json(["error" => "false", ...$games], 200);

    }

    // custom request handler will be made in future
    public function storeGame(Request $request) {
        $validated = $request->validate([
            'name' => 'required',
            'type' => 'required',
            'image_url' => 'required',
            'is_favorite' => 'sometimes' // will be removed later
        ]);
    

        

        try {

            $user = $request->user();

            if ($user->is_audience) {
                return response()->json([
                    'error' => true, 
                    'message' => "unauthorized"
                ], 401);
            }

            $game = Game::create([
                'name'=> $request->name,
                'type' => $validated['is_favorite'],
                'image_url' => $validated['image_url'],
                'is_favorite' => $validated['is_favorite'] // should be removed later
            ]);
           
            // dd($game);

        } catch (\Throwable $throwable) {

            report($throwable);
            return response()->json([
                'error' => 'true',
                'message' => $throwable->getMessage()
            ], 500);
        }

        return response()->json(["error" => "false", $game], 200);
    }

    public function showGame($gameId) {
        try {
           $game = Game::find($gameId)->with('rules');

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

        return response()->json(["error" => "false", $game], 200);
    }

    public function updateGame(Request $request, $game_id) {
        $validated = $request->validate([
            'name' => 'sometimes',
            'image_url' => 'sometimes',
            'type' => 'sometimes'
        ]);


        try {
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
    
            if ($categoryName !== "all") {
                $games = Game::whereDate('created_at', '>=', $start_week)
                    ->whereDate('created_at', '<=', $end_week);
            
            } else {
                $games = Game::where('type', $categoryName)
                    ->whereDate('created_at', '>=', $start_week)
                    ->whereDate('created_at', '<=', $end_week)  
                    ->get();
    
            }
        } catch(\Throwable $throwable) {
            
            report($throwable);
            response()->json([
                "error" => "true",
                "message" => $throwable->getMessage()
            ], 500);
        }

        return response()->json(["error" => "false", ...$games], 200);

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


    public function indexFavorite(Request $request)
    {   
        try {
            $audience = $request->user();
    
            $favoriteGames = Game::where('user_id', $audience->id)
                ->where('is_favorite', true)
                ->get();

        } catch(\Throwable $throwable) {
            
            report($throwable);
            response()->json([
                "error" => "true",
                "message" => $throwable->getMessage()
            ], 500);
        }

        return response()->json([
            "error" => "false", 
            $favoriteGames
        ], 200);
    }

    public function gamesByType() {
        try {
            $games = Game::groupBy('type');

        } catch(\Throwable $throwable) {
            
            report($throwable);
            response()->json([
                "error" => "true",
                "message" => $throwable->getMessage()
            ], 500);
        }

        return response()->json([
            "error" => "false", 
            $games
        ], 200);

    }

}
