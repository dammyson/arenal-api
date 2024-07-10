<?php

namespace App\Http\Controllers\leaderboard;

use Illuminate\Http\Request;
use App\Models\CampaignLeaderboard;
use App\Http\Controllers\Controller;
use App\Http\Requests\LeaderboardRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CampaignLeaderboardController extends Controller
{

    public function showDaily($campaign_id)
    {
        try {
            // generate leaderboard
            $leaderboard =  CampaignLeaderboard::where('campaign_id', $campaign_id)
                                ->whereDate('created_at', Carbon::now()->toDateString())
                                ->orderBy('total_points', 'DESC')
                                ->orderBy('play_durations', 'ASC')
                                ->take(6)
                                ->get();
           
        
        } catch (\Exception $exception) {
           report($exception);
            return response()->json(['error'=>true, 'message' => $exception->getMessage()], 500);
        }
        return response()->json(['error'=>false, 'message' => "Daily leaderboard", 'data' => $leaderboard], 200);
    }

    //
    public function storeLeaderBoard(LeaderboardRequest $request, $campaign_id)
    {

        $audience = $request->user();

        try {
            /*
             * check if audience is already on today's leaderboard for the current campaign_id
             * if audience is on leaderboard update the record
             * else create a new leaderboard record for audience for the current campaign_id
             * */
            $leaderboard = CampaignLeaderboard::where('campaign_id', $campaign_id)
                                ->where('audience_id', $audience->id)
                                ->whereDate('created_at', date('Y-m-d'))->first();

            


            if ($leaderboard) {
              
                $leaderboard->play_durations += $request['play_durations'];
                $leaderboard->play_points = $request['play_points'];
                $leaderboard->total_points += $request['play_points'];
                $leaderboard->save();
            } else {

                
                $leaderboard = CampaignLeaderboard::create([
                    'campaign_id' => $campaign_id,
                    'audience_id' => $audience->id,
                    'play_points' => $request['play_points'],
                    'play_durations' => $request['play_durations'],
                    'total_points' => $request['play_points']
                ]);
            }

        } catch (\Throwable $th) {
           Report($th);
            return response()->json(['error' => true, 'message' => 'something went wrong'], 500);
        }
        return response()->json(['error' => false, 'message' => 'Leaderboard record generated', 'data' => $leaderboard], 201);
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

    public function showWeekly($campaign_id)
    {
        try {
            
            // generate leaderboard
            $start_week = Carbon::now()->startOfWeek()->format('Y-m-d');
            $end_week = Carbon::now()->endOfWeek()->format('Y-m-d');

            $leaderboard = DB::table('campaign_leaderboards')
                                ->select('audience_id', DB::raw('SUM(total_points) AS total_points, SUM(play_durations) AS play_durations'))
                                ->where('campaign_id', $campaign_id)
                                ->whereDate('created_at', '>=', $start_week)->whereDate('created_at', '<=', $end_week)
                                ->groupBy('audience_id')
                                ->orderBy('total_points', 'DESC')
                                ->orderBy('play_durations', 'ASC')
                                ->take(6)
                                ->get();
            
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error'=>true, 'message' => $exception->getMessage()], 500);
        }
        return response()->json(['error'=>false, 'message' => "Weekly leaderboard", 'data' => $leaderboard], 200);
    }

    public function showMonthly($campaign_id)
    {
        try {
            
            // generate leaderboard
            $start_month = Carbon::now()->firstOfMonth()->format('Y-m-d');
            $end_month = Carbon::now()->lastOfMonth()->format('Y-m-d');

            $leaderboard =  DB::table('campaign_leaderboards')
                                ->select('audience_id', DB::raw('SUM(total_points) AS total_points, SUM(play_durations) AS play_durations'))
                                ->where('campaign_id', $campaign_id)
                                ->whereDate('created_at', '>=', $start_month)->whereDate('created_at', '<=', $end_month)
                                ->groupBy('audience_id')
                                ->orderBy('total_points', 'DESC')
                                ->orderBy('play_durations', 'ASC')
                                ->take(6)
                                ->get();
           
        } catch (\Throwable $th) {
            report($th);
            return response()->json(['error'=>true, 'message' => 'something went wrong'], 500);
        }
        return response()->json(['error'=> false, 'message' => "Monthly leaderboard", 'data' => $leaderboard], 200);
    }

    public function showAllTime($campaign_id)
    {
        try {
            
            // generate leaderboard
            $leaderboard =  DB::table('campaign_leaderboards')
                                ->select('audience_id', DB::raw('SUM(total_points) AS total_points, SUM(play_durations) AS play_durations'))
                                ->where('campaign_id', $campaign_id)
                                ->groupBy('audience_id')
                                ->orderBy('total_points', 'DESC')
                                ->orderBy('play_durations', 'ASC')
                                ->take(6)
                                ->get();


        } catch (\Throwable $th) {
            report($th);
            return response()->json(['error'=>true, 'message' => 'something went wrong'], 500);
        }
        return response()->json(['error'=>false, 'message' => "All-time leaderboard", 'data' => $leaderboard], 200);
    }

    public function convertMilliToSeconds($milli_sec)
    {
        return number_format(($milli_sec / 1000), 2);
    }

    public function leaderboardTopThree() {
        try {
            $topThree = DB::table('campaign_leaderboards')
                            ->select('audience_id', DB::raw('SUM(total_points) AS total_points, SUM(play_durations) AS play_durations'))
                            ->orderBy('total_points', 'DESC')
                            ->orderBy('play_durations', 'ASC')
                            ->groupBy('audience_id')
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
            'message' => 'top 3 players',
            'players' => $topThree
        ]);
        
    }
}
