<?php

namespace App\Http\Controllers;

use App\Models\BrandPoint;
use App\Models\Campaign;
use App\Models\CampaignGamePlay;
use App\Services\Utility\CheckDailyBonusService;
use App\Services\Utility\GetArenaAudienceBadgeListService;
use App\Services\Utility\GetTestAudienceCurrentAndNextBadge;
use App\Services\Utility\LeaderboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlayGameController extends BaseController
{
    
    public function playRecallAndMatch(Request $request, Campaign $campaign)
    {
        
        try {

            $request->validate([
                'difficulty' => 'required|in:easy,medium,hard',
                'game_id' => 'required',
                "percentage_of_completion" => "required|numeric|min:0|max:1",
                "total_matched_image" => "required|integer|min:1",
            ]);
            
            // $points = 100;
            $brandId = $campaign->brand_id;
            $gameId = $request->input('game_id');
            $difficulty = $request->input('difficulty');            
            $percentageOfCompletedWords = $request->input('percentage_of_completion');
            $totalMatchImage = $request->input('total_matched_image');

            $difficultyMultipliers = [
                'easy' => [
                   "regular_point" => 2,
                   "max_point" => 70
                ],
                'medium' => [
                    "regular_point" => 3,
                    "max_point" => 100
                ],
                'hard' => [
                    "regular_point" => 4,
                    "max_point" => 150     
                ]
            ];

            $mode = $difficultyMultipliers[$difficulty];  

            $points = 0;
            if ($percentageOfCompletedWords < 1) {
                $points = (int) floor(($percentageOfCompletedWords * $totalMatchImage ) * $mode['regular_point']);
                // dd($points);
            } else {
                $points = $mode['max_point'];
            }

            // dump($points);

            $audience = $request->user();
            // dump($points);
            [ $isHighScore, $highScoreBonus ] = (new CheckDailyBonusService())->checkHighScore($audience->id, $points, $brandId, true);
            // dump($highScoreBonus);
            if ($isHighScore) {
                $points += $highScoreBonus; 
            }
            // dd($points);
              
            [$eligibilityStatus, $bonusId] = (new CheckDailyBonusService())->checkEligibility($brandId, $gameId, $audience->id, true);
            // dd($eligibilityStatus, $bonusId);
            
            if ($eligibilityStatus == true) {
                $dailyBonus = (new CheckDailyBonusService())->allocatedDailyBonus($bonusId, $audience->id, $brandId, $gameId, true);
                $points += $dailyBonus;
            }
            // dump($points);
           
            // Start a transaction
            // Start a transaction
            DB::beginTransaction();
                $campaignGamePlay = (new LeaderboardService())->storeLeaderboard($audience->id, $campaign->id, $gameId, $brandId,  true, $points);
            

                // Commit the transaction after updates

                $audienceBrandPoint = BrandPoint::where('is_arena', true)        
                    ->where("audience_id", $audience->id)
                    ->first();

                if ($audienceBrandPoint) {
                    $audienceBrandPoint->points += $points;
                    $audienceBrandPoint->save();
            
                } else {
                    $audienceBrandPoint = BrandPoint::create([
                        'is_arena' => true,
                        'audience_id' => $audience->id,
                        'brand_id' => $brandId,
                        'points' => $points
                    ]);
                }

                // [$currentBadge, $nextBadge] = (new GetAudienceCurrentAndNextBadge($brandId, $audienceBrandPoint->points))->run();
                [$currentBadge, $nextBadge] = (new GetTestAudienceCurrentAndNextBadge($brandId, $audienceBrandPoint->points, true))->run();

                $audienceBadgesList = (new GetArenaAudienceBadgeListService($brandId, $audience->id, $audienceBrandPoint->points, true))->run();
        
            DB::commit();

            $data = [
                "leaderboard" => $campaignGamePlay,
                "user_points" => $audienceBrandPoint?->points,
                "point_earned" => $points,
                "current_badge" => $currentBadge,
                "next_badge" => $nextBadge,    
                'high_score_bonus' => $highScoreBonus ?? null,
                'daily_bonus' => $dailyBonus ?? null,
                "audience_badges_list" => $audienceBadgesList
            ];
            return $this->sendResponse($data, "recall play successfully");
        } catch (\Throwable $e) {

            DB::rollBack();
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }

       
    }
}
