<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Game;
use App\Models\CampaignGame;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\FuncCall;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StoreGameRequest;
use App\Services\Games\IndexGameService;
use App\Services\Games\StoreGameService;
use App\Services\Games\UpdateGameService;
use App\Services\Games\ToogleFavoriteGame;
use App\Http\Requests\Game\UpdateGameRequest;
use App\Services\CampaignGame\FilterCampaignGame;

class GameController extends BaseController
{
    public function index() {

        try {
           Gate::authorize('is-user');
           $data = (new IndexGameService())->run();            

        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }
        
        return $this->sendResponse($data, "Game info retrieved succcessfully");

    }

   
    public function storeGame(StoreGameRequest $request) {
       try {         
            $data = (new StoreGameService($request))->run();

        }  catch (\Exception $e){

            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "Game created retrieved succcessfully", 201);

    }

    public function showGame($gameId) {
        try {

            $data = Game::where('id', $gameId)->with('rules')->first();

            if (!$data) {
                return response()->json(['error' => 'false', 'message' => 'no game found'], 404);
            }
        
        }  catch (\Exception $e){

            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }         
        return $this->sendResponse($data, "Game retrieved succcessfully");

    }

    public function updateGame(UpdateGameRequest $request, $gameId) {
        // try {
        //   Gate::authorize('is-audience');
        //     $data = (new UpdateGameService($request, $gameId))->run();

        // }  catch (\Exception $e){
        //     return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        // }         
        // return $this->sendResponse($data, "Game retrieved succcessfully");
    
        try {
            $game = Game::find($gameId);

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
            $data = (new FilterCampaignGame($validated['type']))->run();

        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "Game retrieved succcessfully");
    
    }


    public function toogleFavorite($gameId) {
        try {
            $data = (new ToogleFavoriteGame($gameId))->run();
        
        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "Game state changed succcessfully");

    }



    public function gamesByType() {
         // Retrieve CampaignGame models and join with games to group by game type
        try{
            $data = CampaignGame::with('game')
                ->get();

        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "Game retrieved by type");

    }
    


}
