<?php

namespace App\Services\Achievement;

use App\Models\ArenaAudienceReward;
use App\Models\Badge;
use App\Models\BrandPoint;
use Illuminate\Http\Request;
use App\Services\BaseServiceInterface;
use App\Services\Utility\TestGetAudienceRankService;
use App\Services\Utility\GetArenaAudienceBadgeListService;
use App\Services\Utility\GetTestAudienceCurrentAndNextBadge;

class TestAudienceBrandAchievementService implements BaseServiceInterface{
    protected $brandId;
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function run() {
        $audienceId =  $this->request->user()->id;

        
        $audienceReward = ArenaAudienceReward::where('audience_id', $audienceId)
            ->get();

        // $audienceBadges = AudienceBadge::where('brand_id', $this->brandId)
        //     ->where('audience_id', $audienceId)
        //     ->with('badge:id,name')
        //     ->get();



        $audienceBrandPoint = BrandPoint::where('is_arena', true)        
            ->where("audience_id", $audienceId)
            ->first();



     
        $points = $audienceBrandPoint->points ?? 0;
        
        // $allBadges = Badge::where('brand_id', $this->brandId)->get();
        $allBadges = Badge::where('is_arena', true)->get();


    //    dd($this->brandId, $points);
        [$currentBadge, $nextBadge] = (new GetTestAudienceCurrentAndNextBadge(null, $points, true))->run();


        // $audienceBadgesList = AudienceBadge::where('audience_id', $audienceId)->where('is_arena', true)->with('badge')->get();
        $audienceBadgesList = (new GetArenaAudienceBadgeListService(null, $audienceId, $points, true))->run();;

       
        $rank = (new TestGetAudienceRankService($audienceId))->run();


        return ["point" => $points, "rank" => $rank, "current_badge" => $currentBadge, "next_badge" => $nextBadge,  "badges" => $allBadges , "audience_badges" => $audienceBadgesList, "audience_reward" => $audienceReward];

    }
}