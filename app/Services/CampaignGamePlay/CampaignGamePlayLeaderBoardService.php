<?php
namespace App\Services\CampaignGamePlay;

use Carbon\Carbon;
use App\Models\CampaignGamePlay;
use Illuminate\Support\Facades\DB;

class CampaignGamePlayLeaderBoardService {
    
    public function gameLeaderboardAllTime($campaignId, $gameId)
    {
        $leaderboard = CampaignGamePlay::select('user_id', DB::raw('SUM(score) as total_score'))
            ->where('campaign_id', $campaignId)
            ->where('game_id', $gameId)
            ->groupBy('user_id')
            ->orderBy('total_score', 'desc')
            ->with('user') // Assuming you have a relationship with the User model
            ->get();

        

        return $leaderboard;
    }

    public function gameLeaderboardDaily($campaignId, $gameId)
    {
        return CampaignGamePlay::select('user_id', DB::raw('SUM(score) as total_score'))
            ->where('campaign_id', $campaignId)
            ->where('game_id', $gameId)
            ->whereDate('created_at', Carbon::now()->toDateString())
            ->groupBy('user_id')
            ->orderBy('total_score', 'desc')
            ->with('user') // Assuming you have a relationship with the User model
            ->get();

       }

    public function gameLeaderboardWeekly($campaignId, $gameId)
    {
        $start_week = Carbon::now()->startOfWeek()->format('Y-m-d');
        $end_week = Carbon::now()->endOfWeek()->format('Y-m-d');

        return CampaignGamePlay::select('user_id', DB::raw('SUM(score) as total_score'))
            ->where('campaign_id', $campaignId)
            ->where('game_id', $gameId)
            ->whereDate('created_at', '>=', $start_week)->whereDate('created_at', '<=', $end_week)                              
            ->groupBy('user_id')
            ->orderBy('total_score', 'desc')
            ->with('user') // Assuming you have a relationship with the User model
            ->get();
     

    }

    public function gameLeaderboardMonthly($campaignId, $gameId)
    {
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

       

        return $leaderboard;
    }
}