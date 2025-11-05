<?php

namespace App\Services\Utility;

use App\Models\ArenaAudienceBadges;
use App\Models\Badge;
use App\Models\BrandPoint;
use App\Models\AudienceBadge;
use App\Models\CampaignGamePlay;
use App\Services\BaseServiceInterface;

class ProcessArenaAudienceRewardService implements BaseServiceInterface
{
    protected $brandId;
    protected $audienceId;
    protected $points;
    protected $isArena;
    public function __construct($brandId, $audienceId, $points, $isArena)
    {
        $this->brandId = $brandId;
        $this->audienceId = $audienceId;
        $this->points = $points;
        $this->isArena = $isArena;
        
    }

    public function run()
    {   

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
                        'campaign_id' => $campaignId,
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
        DB::commit();

         // $points = 100;
        $prize = Prize::where('is_arena', true)
            ->where('points', '<=', $points)
            ->inRandomOrder()
            ->first();
            
        // return $prize;
        
        if ($prize) {
            $brandAudienceReward = ArenaAudienceReward::create([
                'game_id' => $gameId,
                'audience_id' => $audience->id,
                'prize_name' => $prize->name,
                'prize_code' => $this->generatePrizeCode(),
                'is_redeemed' => false
            ]);

            // $brandAudienceReward->load('prize:id,name,description');
            // $brandAudienceReward = null;
        } 
        
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
        

        

    }
    
}
