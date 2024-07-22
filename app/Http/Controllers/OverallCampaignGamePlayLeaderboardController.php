<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CampaignGamePlay;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OverallCampaignGamePlayLeaderboardController extends Controller
{
    
    public function overallLeaderboard()
    {
        try {
            $leaderboard = CampaignGamePlay::select('user_id', DB::raw('SUM(score) as total_score'))
                ->groupBy('user_id')
                ->orderBy('total_score', 'desc')
                ->with('user') // Assuming you have a relationship with the User model
                ->get();

        } catch (\Throwable $th) {
            report($th);
            return response()->json([
                'error'=>true, 
                'message' => 'something went wrong',
                'messages' => $th->getMessage()
            ], 500);
        }

        return response()->json($leaderboard);
    }

    public function overallGamePlayTopThree() 
    {
        try {
            $topThreeleaderboard = CampaignGamePlay::select('user_id', DB::raw('SUM(score) as total_score'))
                ->groupBy('user_id')
                ->orderBy('total_score', 'desc')
                ->with('user') // Assuming you have a relationship with the User model
                ->take(3)
                ->get();

        } catch (\Throwable $th) {
            report($th);
            return response()->json([
                'error'=>true, 
                'message' => 'something went wrong',
                'messages' => $th->getMessage()
            ], 500);
        }

        return response()->json([
            'error' => false,
            'top_three' => $topThreeleaderboard
        ]);  
    }

    public function overallLeaderboardDaily()
    {
        try {
            $leaderboard = CampaignGamePlay::select('user_id', DB::raw('SUM(score) as total_score'))
                ->whereDate('created_at', Carbon::now()->toDateString())
                ->groupBy('user_id')
                ->orderBy('total_score', 'desc')
                ->with('user') // Assuming you have a relationship with the User model
                ->get();

        } catch (\Throwable $th) {
            report($th);
            return response()->json([
                'error'=>true, 
                'message' => 'something went wrong',
                'messages' => $th->getMessage()
            ], 500);
        }

        return response()->json($leaderboard);
    }

    public function overallLeaderboardWeekly()
    {
        try {
            $start_week = Carbon::now()->startOfWeek()->format('Y-m-d');
            $end_week = Carbon::now()->endOfWeek()->format('Y-m-d');
    
            $leaderboard = CampaignGamePlay::select('user_id', DB::raw('SUM(score) as total_score'))
                ->whereDate('created_at', '>=', $start_week)->whereDate('created_at', '<=', $end_week)                              
                ->groupBy('user_id')
                ->orderBy('total_score', 'desc')
                ->with('user') // Assuming you have a relationship with the User model
                ->get();

        }  catch (\Throwable $th) {
            report($th);
            return response()->json([
                'error'=>true, 
                'message' => 'something went wrong',
                'messages' => $th->getMessage()
            ], 500);
        }

        return response()->json($leaderboard);
    }

    public function overallLeaderboardMonthly()
    {
        try {
            $start_month = Carbon::now()->firstOfMonth()->format('Y-m-d');
            $end_month = Carbon::now()->lastOfMonth()->format('Y-m-d');
    
            $leaderboard = CampaignGamePlay::select('user_id', DB::raw('SUM(score) as total_score'))
                ->whereDate('created_at', '>=', $start_month)->whereDate('created_at', '<=', $end_month)
                ->groupBy('user_id')
                ->orderBy('total_score', 'desc')
                ->with('user') // Assuming you have a relationship with the User model
                ->get();

        } catch (\Throwable $th) {
            report($th);
            return response()->json([
                'error'=>true, 
                'message' => 'something went wrong',
                'messages' => $th->getMessage()
            ], 500);
        }

        return response()->json($leaderboard);
    }
}
