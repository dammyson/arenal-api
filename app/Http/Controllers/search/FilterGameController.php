<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\CampaignGame;
use App\Http\Requests\Search\FilterGameRequest;

class FilterGameController extends Controller
{
    public function filter(FilterGameRequest $request) {
       
        try {

            $start_week = Carbon::now()->startOfWeek()->format('Y-m-d');
            $end_week = Carbon::now()->endOfWeek()->format('Y-m-d');
           
            if ($request->type == "all")  {
                $campaignGames = CampaignGame::whereDate('created_at', '>=', $start_week)
                                    ->whereDate('created_at', '<=', $end_week)->get();
            
            } else {
                $campaignGames = CampaignGame::whereHas('game', function ($query) use($request, $start_week, $end_week){
                    $query->where('type', $request->type)
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
       

        return response()->json([
            "error" => "false", 
            $campaignGames
        ], 200);
    }
}


