<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Badge;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Models\CampaignGamePlay;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\GamePlayRequest;
use App\Http\Requests\Campaign\UpdateCampaignGamePlayRequest;
use App\Services\CampaignGamePlay\ArenaLeaderboardService;

class CampaignGamePlayLeaderboardController extends BaseController
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
      
        try{

            $audience = $request->user();
            $filter = $request->query('filter');
            $leaderboard = CampaignGamePlay::with(['audience', 'audience.audienceBadges' => function($q) use($brand) { 
                        $q->where('brand_id', $brand->id)
                            ->with(['badge' => function ($b) {
                                $b->select('id', 'name', 'image_url', 'points');
                            }])
                            ->orderBy(
                                // order audience_badges by their related badge points (DESC)
                                Badge::select('points')
                                    ->whereColumn('badges.id', 'audience_badges.badge_id'),
                                'desc'
                            )
                            // optional tiebreaker so equal points keep newest first
                            ->orderBy('audience_badges.created_at', 'desc');
                    }]) 
                // with(['audience', 'audience.audienceBadges.badge:id,name']) // Assuming you have a relationship with the Audience model
                    ->select('audience_id', DB::raw('SUM(score) as total_score'))
                    ->where('brand_id', $brand->id);

                // return $leaderboard->get();

           if ($filter == "daily") {
                $leaderboard->whereDate('updated_at', Carbon::now()->toDateString());
                  
           } else if ($filter == "weekly") {
                $start_week = Carbon::now()->startOfWeek()->format('Y-m-d');
                $end_week = Carbon::now()->endOfWeek()->format('Y-m-d');

                $leaderboard->whereDate('updated_at', '>=', $start_week)->whereDate('created_at', '<=', $end_week);

           } else if ($filter == 'monthly') {            
                $start_month = Carbon::now()->firstOfMonth()->format('Y-m-d');
                $end_month = Carbon::now()->lastOfMonth()->format('Y-m-d');
                $leaderboard->whereDate('updated_at', '>=', $start_month)->whereDate('created_at', '<=', $end_month);
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

    
    public function testArenaLeaderboard(Request $request)
    {
      
        try{

            $audience = $request->user();
            $filter = $request->query('filter');

            $leaderboard = CampaignGamePlay::with(['audience', 'audience.audienceBadges' => function($q)  { 
                        $q->where('is_arena', true)
                            ->with(['badge' => function ($b) {
                                $b->select('id', 'name', 'image_url', 'points');
                            }])
                            ->orderBy(
                                // order audience_badges by their related badge points (DESC)
                                Badge::select('points')
                                    ->whereColumn('badges.id', 'audience_badges.badge_id'),
                                'desc'
                            )
                            // optional tiebreaker so equal points keep newest first
                            ->orderBy('audience_badges.created_at', 'desc');
                    }]) 
                    ->select('audience_id', DB::raw('SUM(score) as total_score'))
                    ->where('is_arena', true);

                // return $leaderboard->get();

           if ($filter == "daily") {
                $leaderboard->whereDate('updated_at', Carbon::now()->toDateString());
                  
           } else if ($filter == "weekly") {
                $start_week = Carbon::now()->startOfWeek()->format('Y-m-d');
                $end_week = Carbon::now()->endOfWeek()->format('Y-m-d');

                $leaderboard->whereDate('updated_at', '>=', $start_week)->whereDate('created_at', '<=', $end_week);

           } else if ($filter == 'monthly') {            
                $start_month = Carbon::now()->firstOfMonth()->format('Y-m-d');
                $end_month = Carbon::now()->lastOfMonth()->format('Y-m-d');
                $leaderboard->whereDate('updated_at', '>=', $start_month)->whereDate('created_at', '<=', $end_month);
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

    public function arenaLeaderboard(Request $request, Brand $brandId) {       

        $data = (new ArenaLeaderboardService($request, $brandId))->run();

        return $this->sendResponse($data, "arena leaderboard");
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