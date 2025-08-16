<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Models\CampaignGamePlay;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\GamePlayRequest;
use App\Http\Requests\Campaign\UpdateCampaignGamePlayRequest;

class CampaignGamePlayLeaderboardController extends Controller
{
    public function gameLeaderboardAllTime($campaignId, $gameId)
    {
        try {

            $leaderboard = CampaignGamePlay::select('audience_id', DB::raw('SUM(score) as total_score'))
                ->where('campaign_id', $campaignId)
                ->where('game_id', $gameId)
                ->groupBy('audience_id')
                ->orderBy('total_score', 'desc')
                ->with('audience') // Assuming you have a relationship with the Audience model
                ->get();

        }  catch (\Throwable $th) {
            return response()->json([
                'error' => true,
                'message' => $th->getMessage()
            ], 500);
        }

        return response()->json($leaderboard);
    }

    public function brandLeaderboard(Request $request, Brand $brand)
    {
        try {

            $audience = $request->user();
            $filter = $request->query('filter');
            $leaderboard = CampaignGamePlay::with(['audience', 'audience.audienceBadges.badge:id,name']) // Assuming you have a relationship with the Audience model
                    ->select('audience_id', DB::raw('SUM(score) as total_score'))
                    ->where('brand_id', $brand->id);

           if ($filter == "daily") {
                   $leaderboard->whereDate('created_at', Carbon::now()->toDateString());
                  
           } else if ($filter == "weekly") {
                $start_week = Carbon::now()->startOfWeek()->format('Y-m-d');
                $end_week = Carbon::now()->endOfWeek()->format('Y-m-d');

                $leaderboard->whereDate('created_at', '>=', $start_week)->whereDate('created_at', '<=', $end_week);

           } else if ($filter == 'monthly') {            
                $start_month = Carbon::now()->firstOfMonth()->format('Y-m-d');
                $end_month = Carbon::now()->lastOfMonth()->format('Y-m-d');
                $leaderboard->whereDate('created_at', '>=', $start_month)->whereDate('created_at', '<=', $end_month);
           } 

            $leaderboard = $leaderboard->groupBy('audience_id')
                ->orderBy('total_score', 'desc')
                ->get();

        }   catch (\Throwable $th) {
            return response()->json([
                'error' => true,
                'message' => $th->getMessage()
            ], 500);
        }

        return response()->json(["audience" => $audience->id, "leaderboard" => $leaderboard]);
    }

    public function gameLeaderboardDaily($campaignId, $gameId)
    {
        try {

            $leaderboard = CampaignGamePlay::select('audience_id', DB::raw('SUM(score) as total_score'))
                ->where('campaign_id', $campaignId)
                ->where('game_id', $gameId)
                ->whereDate('created_at', Carbon::now()->toDateString())
                ->groupBy('audience_id')
                ->orderBy('total_score', 'desc')
                ->with('audience') // Assuming you have a relationship with the Audience model
                ->get();

        }   catch (\Throwable $th) {
            return response()->json([
                'error' => true,
                'message' => $th->getMessage()
            ], 500);
        }

        return response()->json($leaderboard);
    }

    public function gameLeaderboardWeekly($campaignId, $gameId)
    {
        try {

            $start_week = Carbon::now()->startOfWeek()->format('Y-m-d');
            $end_week = Carbon::now()->endOfWeek()->format('Y-m-d');
    
            $leaderboard = CampaignGamePlay::select('audience_id', DB::raw('SUM(score) as total_score'))
                ->where('campaign_id', $campaignId)
                ->where('game_id', $gameId)
                ->whereDate('created_at', '>=', $start_week)->whereDate('created_at', '<=', $end_week)                              
                ->groupBy('audience_id')
                ->orderBy('total_score', 'desc')
                ->with('audience') // Assuming you have a relationship with the User model
                ->get();

        }  catch (\Throwable $th) {
            return response()->json([
                'error' => true,
                'message' => $th->getMessage()
            ], 500);
        }

        return response()->json($leaderboard);
    }

    public function gameLeaderboardMonthly($campaignId, $gameId)
    {
        try {
      
            $start_month = Carbon::now()->firstOfMonth()->format('Y-m-d');
            $end_month = Carbon::now()->lastOfMonth()->format('Y-m-d');
    
            $leaderboard = CampaignGamePlay::select('audience_id', DB::raw('SUM(score) as total_score'))
                ->where('campaign_id', $campaignId)
                ->where('game_id', $gameId)
                ->whereDate('created_at', '>=', $start_month)->whereDate('created_at', '<=', $end_month)
                ->groupBy('audience_id')
                ->orderBy('total_score', 'desc')
                ->with('audience') // Assuming you have a relationship with the User model
                ->get();

        }  catch (\Throwable $th) {
            return response()->json([
                'error' => true,
                'message' => $th->getMessage()
            ], 500);
        }

        return response()->json($leaderboard);
    }

}