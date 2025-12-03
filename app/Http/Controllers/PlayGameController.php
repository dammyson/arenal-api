<?php

namespace App\Http\Controllers;

use App\Models\BrandPoint;
use App\Models\Campaign;
use App\Models\CampaignGamePlay;
use App\Services\Utility\CheckDailyBonusService;
use App\Services\Utility\GetArenaAudienceBadgeListService;
use App\Services\Utility\GetTestAudienceCurrentAndNextBadge;
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

            
            if ($percentageOfCompletedWords < 1) {
                $points = ($percentageOfCompletedWords * $totalMatchImage ) * $mode['regular_point'];

            } else {
                $points = $mode['max_point'];
            }

            $audience = $request->user();
            // dump($points);

              
            [$eligibilityStatus, $bonusId] = (new CheckDailyBonusService())->checkEligibility($brandId, $audience->id, true);
            // dd($eligibilityStatus);
            
            if ($eligibilityStatus == true) {
                $dailyBonus = (new CheckDailyBonusService())->allocatedDailyBonus($bonusId, $audience->id, $brandId, true);
                $points += $dailyBonus;
            }
            // dump($points);
           
              // Start a transaction
            DB::beginTransaction();

                
                // Fetch the record and lock it for update
                $campaignGamePlay = CampaignGamePlay::where('audience_id', $audience->id)
                    ->where('is_arena', true)
                    ->lockForUpdate()  // Apply pessimistic locking
                    ->first();

                if (!$campaignGamePlay) {
                    // If no record exists, create a new one
                    $campaignGamePlay = CampaignGamePlay::create([
                        'audience_id' => $audience->id,
                        'is_arena'=> true,
                        'campaign_id' => $campaign->id,
                        'game_id' => $gameId,
                        'brand_id' => $brandId,
                        'score' => $points,
                        'played_at' => now()
                    ]);
                    
                } else {
                    // If record exists, increment score and update played_at
                    $campaignGamePlay->score += $points;
                    $campaignGamePlay->played_at = now();
                    $campaignGamePlay->save();
                }
            

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
                "current_badge" => $currentBadge,
                "next_badge" => $nextBadge,                
                'daily_bonus' => $dailyBonus ?? null,
                "audience_badges_list" => $audienceBadgesList
            ];
            return $this->sendResponse($data, "trivia reward allocated successfully");
        } catch (\Throwable $e) {

            DB::rollBack();
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }

        // Return the response
        return response()->json([
            'message' => 'Game played successfully',
            'score' => $score,
        ]);
    }
}
