<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
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
            Gate::authorize('is-audience');
            $leaderboard = CampaignGamePlay::select('user_id', DB::raw('SUM(score) as total_score'))
                ->where('campaign_id', $campaignId)
                ->where('game_id', $gameId)
                ->groupBy('user_id')
                ->orderBy('total_score', 'desc')
                ->with('user') // Assuming you have a relationship with the User model
                ->get();

        }  catch (\Throwable $th) {
            return response()->json([
                'error' => true,
                'message' => $th->getMessage()
            ], 500);
        }

        return response()->json($leaderboard);
    }

    public function gameLeaderboardDaily($campaignId, $gameId)
    {
        try {

            Gate::authorize('is-audience');

            $leaderboard = CampaignGamePlay::select('user_id', DB::raw('SUM(score) as total_score'))
                ->where('campaign_id', $campaignId)
                ->where('game_id', $gameId)
                ->whereDate('created_at', Carbon::now()->toDateString())
                ->groupBy('user_id')
                ->orderBy('total_score', 'desc')
                ->with('user') // Assuming you have a relationship with the User model
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
            Gate::authorize('is-audience');

            $start_week = Carbon::now()->startOfWeek()->format('Y-m-d');
            $end_week = Carbon::now()->endOfWeek()->format('Y-m-d');
    
            $leaderboard = CampaignGamePlay::select('user_id', DB::raw('SUM(score) as total_score'))
                ->where('campaign_id', $campaignId)
                ->where('game_id', $gameId)
                ->whereDate('created_at', '>=', $start_week)->whereDate('created_at', '<=', $end_week)                              
                ->groupBy('user_id')
                ->orderBy('total_score', 'desc')
                ->with('user') // Assuming you have a relationship with the User model
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

            Gate::authorize('is-audience');
            
            $start_month = Carbon::now()->firstOfMonth()->format('Y-m-d');
            $end_month = Carbon::now()->lastOfMonth()->format('Y-m-d');
    
            $leaderboard = CampaignGamePlay::select('user_id', DB::raw('SUM(score) as total_score'))
                ->where('campaign_id', $campaignId)
                ->where('game_id', $gameId)
                ->whereDate('created_at', '>=', $start_month)->whereDate('created_at', '<=', $end_month)
                ->groupBy('user_id')
                ->orderBy('total_score', 'desc')
                ->with('user') // Assuming you have a relationship with the User model
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
