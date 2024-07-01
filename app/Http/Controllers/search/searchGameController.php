<?php

namespace App\Http\Controllers\search;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Search\SearchGameRequest;
use App\Models\CampaignGame;

class SearchGameController extends Controller
{
    
    public function searchGame(SearchGameRequest $request) {

        try {
            
           $campaignGame = CampaignGame::wherehas('game', function ($query) use($request) {
                $query->whereAny([
                    'name',
                    'type'
                ], 'LIKE', '%'. $request['search-input']. '%');
                
           })->with('game')->get();;
         

        } catch(\Throwable $throwable) {
            
            report($throwable);
            response()->json([
                "error" => "true",
                "message" => $throwable->getMessage()
            ], 500);
        }

        return response()->json([
            "error" => "false",
            "campaign_game" => $campaignGame
        ], 200);

       
    }
}
