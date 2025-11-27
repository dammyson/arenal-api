<?php

namespace App\Http\Controllers;

use App\Models\BrandPoint;
use App\Models\Campaign;
use App\Models\CampaignGamePlay;
use App\Services\Utility\GetArenaAudienceBadgeListService;
use App\Services\Utility\GetTestAudienceCurrentAndNextBadge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlayGameController extends BaseController
{
    
    public function playRecallAndMatch(Request $request, Campaign $campaign)
    {
        
        try {
        
            // $points = 100;
            $brandId = $campaign->brand_id;
            $gameId = $request->input('game_id');

            $difficulty = $request->input('difficulty');

            $difficultyMultipliers = [
                'easy' => 20,
                'medium' => 30,
                'hard' => 40,
            ];

            $points = $difficultyMultipliers[$difficulty] ?? 0;         


            // dd($points);
           
            $audience = $request->user();
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
                "current_badge" => $currentBadge,
                "next_badge" => $nextBadge,
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
