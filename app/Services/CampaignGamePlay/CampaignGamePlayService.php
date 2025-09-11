<?php

namespace App\Services\CampaignGamePlay;

use Carbon\Carbon;
use App\Models\Prize;
use App\Models\Campaign;
use App\Models\BrandPoint;
use App\Models\CampaignGame;
use App\Models\CampaignGamePlay;
use Illuminate\Support\Facades\DB;
use App\Models\BrandAudienceReward;
use App\Http\Requests\GamePlayRequest;
use App\Http\Requests\TestGamePlayRequest;
use App\Http\Requests\Campaign\StoreCampaignGameRequest;
use App\Services\Utility\GetArenaAudienceBadgeListService;
use App\Services\Utility\GetTestAudienceCurrentAndNextBadge;
use App\Http\Requests\Campaign\UpdateCampaignGamePlayRequest;
use App\Services\Utility\GetArenaAudienceCurrentAndNextBadge;

class CampaignGamePlayService {

    public function __construct()
    {
          
    }

    public function storeCampaignGamePlay(GamePlayRequest $request, $campaignId, $gameId) {
       
        $audienceId = $request->user('audience')->id;
        $score = (int) $request->input('score');
        // $played_at = $request->input('played_at');

        // Start a transaction
        DB::beginTransaction();

        // Fetch the record and lock it for update
        $campaignGamePlay = CampaignGamePlay::where('audience_id', $audienceId)
            ->where('campaign_id', $campaignId)
            ->where('game_id', $gameId)
            ->lockForUpdate()  // Apply pessimistic locking
            ->first();

        if (!$campaignGamePlay) {
            // If no record exists, create a new one
            $campaignGamePlay = CampaignGamePlay::create([
                'audience_id' => $audienceId,
                'campaign_id' => $campaignId,
                'game_id' => $gameId,
                'score' => $score,
                'played_at' => now()
            ]);
        } else {
            // If record exists, increment score and update played_at
            $campaignGamePlay->score += $score;
            $campaignGamePlay->played_at = now();
            $campaignGamePlay->save();
        }

        // Commit the transaction after updates
        DB::commit();
        
        return $campaignGamePlay;
       
    
    }

    public function testStoreCampaignGamePlay(TestGamePlayRequest $request, $campaignId, $gameId) {
       
        $audienceId = $request->user('audience')->id;
        $points = (int) $request->input('score');
        $isArena = $request->input('is_arena');

        // dd($isArena);
        // $played_at = $request->input('played_at');

        // Start a transaction
        DB::beginTransaction();

        // Fetch the record and lock it for update
        $campaignGamePlay = CampaignGamePlay::where('audience_id', $audienceId)
            ->where('is_arena', $isArena)
            ->where('campaign_id', $campaignId)
            ->where('game_id', $gameId)
            ->lockForUpdate()  // Apply pessimistic locking
            ->first();

        if (!$campaignGamePlay) {
           
            // If no record exists, create a new one
            $campaignGamePlay = CampaignGamePlay::create([
                'audience_id' => $audienceId,
                'is_arena' => $isArena,
                'campaign_id' => $campaignId,
                'game_id' => $gameId,
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
        $prize = Prize::where("is_arena", $isArena)
            ->where('points', '<=', $points)
            ->inRandomOrder()
            ->first();
            
        // return $prize;
        $brandId = Campaign::find($campaignId)->brand_id;
        
        if ($prize) {
            $brandAudienceReward = BrandAudienceReward::create([
                'brand_id' => $brandId,
                'audience_id' => $audienceId,
                'prize_id' => $prize->id,
                'is_arena' => $isArena,
                'is_redeemed' => false
            ]);

            $brandAudienceReward->load('prize:id,name,description');
            // $brandAudienceReward = null;
        } 
        
        $audienceBrandPoint = BrandPoint::where('brand_id', $brandId)        
            ->where("audience_id", $audienceId)
            ->where('is_arena', $isArena)
            ->first();
        

        if ($audienceBrandPoint) {
            $audienceBrandPoint->points += $points;
            $audienceBrandPoint->save();
       
        } else {
            $audienceBrandPoint = BrandPoint::create([
                'brand_id' => $brandId,
                'audience_id' => $audienceId,
                'points' => $points
            ]);
        }

        [$currentBadge, $nextBadge] = (new GetTestAudienceCurrentAndNextBadge($brandId, $audienceBrandPoint->points, $isArena))->run();

        $audienceBadgesList = (new GetArenaAudienceBadgeListService($brandId, $audienceId, $audienceBrandPoint->points, $isArena))->run();
            
        return [
            'current_badge' => $currentBadge,
            'next_badge' => $nextBadge,
            "audience_points" => $audienceBrandPoint->points,
            "quiz_point" => $points, 
            "reward" => $brandAudienceReward ?? null, 
            "audience_badges_list" => $audienceBadgesList, 
            "leaderboard" => $campaignGamePlay 
        ];
        
        
        return $campaignGamePlay;
       
    
    }


    public function show($campaignId, $gameId)
    {        
        return CampaignGamePlay::where('campaign_id', $campaignId)
            ->where('game_id', $gameId)
            ->with('game', 'audience', 'campaign')->get();

       
    }
    
    public function update(UpdateCampaignGamePlayRequest $request, $campaignId, $gameId)
    {
        $audience = $request->user('audience');
        $userCampaignGamePlay = CampaignGamePlay::where('campaign_id', $campaignId)
            ->where('game_id', $gameId)
            ->where('audience_id', $audience->id)
            ->first();

        return  $userCampaignGamePlay->update([
            ...$request->validated(),
            'campaign_id' => $campaignId,
            'game_id' => $gameId,
            'audience_id' => $audience->id
        ]);

    }

    public function destroy($campaignId, $gameId)
    {        
        $campaignGamePlay = CampaignGamePlay::where('campaign_id', $campaignId)
            ->where('game_id', $gameId)->first();
        
        $campaignGamePlay->delete();
        return 'deleted';

       
    }
    
    
    
    
}