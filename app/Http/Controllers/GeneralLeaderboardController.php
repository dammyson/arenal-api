<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CampaignLeaderboard;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;


class GeneralLeaderboardController extends Controller
{
    
    public function showDaily()
    {
        try {
            
           
            // generate leaderboard
            $leaderboard =  CampaignLeaderboard::whereDate('created_at', Carbon::now()->toDateString())
                                ->orderBy('total_points', 'DESC')
                                ->orderBy('play_durations', 'ASC')
                                ->take(6)
                                ->get();
           
        
        } catch (\Exception $exception) {
           
            return response()->json(['error'=>true, 'message' => $exception->getMessage()], 500);
        }
        return response()->json(['error'=>false, 'message' => "Daily leaderboard", 'data' => $leaderboard], 200);
    }

      /**
     * maskPhoneNumber
     *
     * @param  mixed $phone
     * @return void
     */
    public function maskPhoneNumber($phone)
    {
        $phone = (string) $phone;
        return Str::substr($phone, 0, 7).'xxxx';
    }

    public function showWeekly()
    {
        try {
            
            // generate leaderboard
            $start_week = Carbon::now()->startOfWeek()->format('Y-m-d');
            $end_week = Carbon::now()->endOfWeek()->format('Y-m-d');

            $leaderboard = DB::table('campaign_leaderboards')
                                ->select('audience_id', DB::raw('SUM(total_points) AS total_points, SUM(play_durations) AS play_durations'))
                                ->whereDate('created_at', '>=', $start_week)->whereDate('created_at', '<=', $end_week)
                                ->groupBy('audience_id')
                                ->orderBy('total_points', 'DESC')
                                ->orderBy('play_durations', 'ASC')
                                ->take(6)
                                ->get();
            
        } catch (\Exception $exception) {
            
            return response()->json(['error'=>true, 'message' => $exception->getMessage()], 500);
        }
        return response()->json(['error'=>false, 'message' => "Weekly leaderboard", 'data' => $leaderboard], 200);
    }

    public function showMonthly()
    {
        try {
            
            // generate leaderboard
            $start_month = Carbon::now()->firstOfMonth()->format('Y-m-d');
            $end_month = Carbon::now()->lastOfMonth()->format('Y-m-d');

            $leaderboard =  DB::table('campaign_leaderboards')
                                ->select('audience_id', DB::raw('SUM(total_points) AS total_points, SUM(play_durations) AS play_durations'))
                                ->whereDate('created_at', '>=', $start_month)->whereDate('created_at', '<=', $end_month)
                                ->groupBy('audience_id')
                                ->orderBy('total_points', 'DESC')
                                ->orderBy('play_durations', 'ASC')
                                ->take(6)
                                ->get();
           
        } catch (\Throwable $th) {
            
            return response()->json(['error'=>true, 'message' => 'something went wrong'], 500);
        }
        return response()->json(['error'=> false, 'message' => "Monthly leaderboard", 'data' => $leaderboard], 200);
    }

    public function showAllTime()
    {
        try {
            
            // generate leaderboard
            $leaderboard =  DB::table('campaign_leaderboards')
                                ->select('audience_id', DB::raw('SUM(total_points) AS total_points, SUM(play_durations) AS play_durations'))
                                ->groupBy('audience_id')
                                ->orderBy('total_points', 'DESC')
                                ->orderBy('play_durations', 'ASC')
                                ->take(6)
                                ->get();


        } catch (\Throwable $th) {
            
            return response()->json(['error'=>true, 'message' => 'something went wrong'], 500);
        }
        return response()->json(['error'=>false, 'message' => "All-time leaderboard", 'data' => $leaderboard], 200);
    }

    public function convertMilliToSeconds($milli_sec)
    {
        return number_format(($milli_sec / 1000), 2);
    }

}
