<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CampaignGamePlay;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Requests\GamePlayRequest;
use App\Http\Requests\Campaign\UpdateCampaignGamePlayRequest;

class CampaignGamePlayLeaderboardController extends Controller
{
    public function gameLeaderboardAllTime($campaignId, $gameId)
    {
        try {
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

   

/////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function updateScore(UpdateCampaignGamePlayRequest $request,  $gameId, $campaignId)
    {
        try {
            $userCampaignGamePlay = CampaignGamePlay::where('campaign_id', $campaignId)
                ->where('game_id', $gameId)
                ->where('user_id', $request->user->id)
                ->first();
            if (!$userCampaignGamePlay) {
                $userCampaignGamePlay = CampaignGamePlay::create([
                    ...$request->validated(),
                    'user_id' => $request->user->id,
                    'campaign_id' => $campaignId,
                    'game_id' => $gameId
                ]);
                
            } else {
                $userCampaignGamePlay->score += $request->validated['score'];
                $userCampaignGamePlay->played_at = $request->validated['played'];
                $userCampaignGamePlay->save();
            } 

        }  catch (\Throwable $th) {
            return response()->json([
                'error' => true,
                'message' => $th->getMessage()
            ], 500);
        }

        // $userCampaignGamePlay->update($request->validated());

        return response()->json([
           'error' => false,
           'userCampaignGamePlay' => $userCampaignGamePlay
        ], 201);
    }
}
