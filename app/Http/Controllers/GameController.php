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
use Illuminate\Support\Facades\Storage;
use App\Services\Games\IndexGameService;
use App\Services\Games\StoreGameService;
use App\Services\Games\UpdateGameService;
use App\Services\Games\ToogleFavoriteGame;
use App\Http\Requests\Game\UpdateGameRequest;
use App\Http\Requests\UploadImageRequest;
use App\Models\RecallMatch;
use App\Services\CampaignGame\FilterCampaignGame;
use App\Services\Games\ShowGameService;
use App\Services\Games\UploadGameImageService;
use App\Services\Images\UploadImageService;

class GameController extends BaseController
{
    public function index() {

        try {
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
        return $this->sendResponse($data, "Game created succcessfully", 201);

    }

    public function showGame($gameId) {
        try {
            $data = (new ShowGameService($gameId))->run();
        
        }  catch (\Exception $e){

            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }         
        return $this->sendResponse($data, "Game retrieved succcessfully");

    }

    public function updateGame(UpdateGameRequest $request, $gameId) {
        try {
            $data = (new UpdateGameService($request, $gameId))->run();

        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }  

        return $this->sendResponse($data, "Game retrieved succcessfully");
         
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
    

    public function uploadImages(UploadImageRequest $request,  $gameId)
    {   
        try {
            $game = (new ShowGameService($gameId))->run();

            $url = (new UploadImageService($request))->run();

            $data = (new UploadGameImageService($game, $url))->run();

        } catch (\Exception $e){

            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }         
        return $this->sendResponse($data, "Game image upload succcessfully");
        
    }
    
    public function storeRecallMatch(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string',
            'image_url' => 'required|string',
            'game_id' => 'required|uuid',
            'campaign_id' => 'required|uuid',
            'entry_fee' => 'sometimes|numeric',
        ]);

        try {
            $recallMatch = RecallMatch::create([...$validated, 
                'user_id' => auth()->user()->id
            ]);

        } catch (\Exception $e){

            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }         
        return $this->sendResponse($recallMatch, "Recall Match created succcessfully", 201);
        
    }


}
