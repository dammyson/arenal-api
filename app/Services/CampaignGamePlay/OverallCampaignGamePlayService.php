<?php

namespace App\Services\CampaignGamePlay;

use Carbon\Carbon;
use App\Models\Campaign;
use App\Models\CampaignGame;
use App\Models\CampaignGamePlay;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Campaign\StoreCampaignGameRequest;

class OverallCampaignGamePlayService {

    public function __construct()
    {
        
    }

    public function overallLeaderboard()
    {
       
        return CampaignGamePlay::select('user_id', DB::raw('SUM(score) as total_score'))
            ->groupBy('user_id')
            ->orderBy('total_score', 'desc')
            ->with('user') // Assuming you have a relationship with the User model
            ->get();
    }

    public function overallGamePlayTopThree() 
    {
        
        return CampaignGamePlay::select('user_id', DB::raw('SUM(score) as total_score'))
            ->groupBy('user_id')
            ->orderBy('total_score', 'desc')
            ->with('user') // Assuming you have a relationship with the User model
            ->take(3)
            ->get();       

    }

    public function overallLeaderboardDaily()
    {
        return CampaignGamePlay::select('user_id', DB::raw('SUM(score) as total_score'))
            ->whereDate('created_at', Carbon::now()->toDateString())
            ->groupBy('user_id')
            ->orderBy('total_score', 'desc')
            ->with('user') // Assuming you have a relationship with the User model
            ->get();

        
    }

    public function overallLeaderboardWeekly()
    {
        
        $start_week = Carbon::now()->startOfWeek()->format('Y-m-d');
        $end_week = Carbon::now()->endOfWeek()->format('Y-m-d');

        return CampaignGamePlay::select('user_id', DB::raw('SUM(score) as total_score'))
            ->whereDate('created_at', '>=', $start_week)->whereDate('created_at', '<=', $end_week)                              
            ->groupBy('user_id')
            ->orderBy('total_score', 'desc')
            ->with('user') // Assuming you have a relationship with the User model
            ->get();

       
    }

    public function overallLeaderboardMonthly()
    {
       
        $start_month = Carbon::now()->firstOfMonth()->format('Y-m-d');
        $end_month = Carbon::now()->lastOfMonth()->format('Y-m-d');
    
        return  CampaignGamePlay::select('user_id', DB::raw('SUM(score) as total_score'))
            ->whereDate('created_at', '>=', $start_month)->whereDate('created_at', '<=', $end_month)
            ->groupBy('user_id')
            ->orderBy('total_score', 'desc')
            ->with('user') // Assuming you have a relationship with the User model
            ->get();
    
    }

}