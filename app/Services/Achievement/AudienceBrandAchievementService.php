<?php

namespace App\Services\Achievement;

use App\Models\Badge;
use App\Models\BrandPoint;
use Illuminate\Http\Request;
use App\Models\AudienceBadge;
use App\Models\BrandAudienceReward;
use App\Services\BaseServiceInterface;
use App\Services\Utility\GetAudienceRankService;
use App\Services\Utility\GetAudienceBadgeListService;
use App\Services\Utility\GetAudienceCurrentAndNextBadge;

class AudienceBrandAchievementService implements BaseServiceInterface{
    protected $brandId;
    protected $request;

    public function __construct(Request $request, $brandId)
    {
        $this->brandId = $brandId;
        $this->request = $request;
    }

    public function run() {
        $audienceId =  $this->request->user()->id;
        $audiencReward = BrandAudienceReward::where('brand_id', $this->brandId)
            ->where('audience_id', $audienceId)
            ->with('prize')
            ->get();


        // $audienceBadges = AudienceBadge::where('brand_id', $this->brandId)
        //     ->where('audience_id', $audienceId)
        //     ->with('badge:id,name')
        //     ->get();



        $audienceBrandPoint = BrandPoint::where('brand_id', $this->brandId)        
            ->where("audience_id", $audienceId)
            ->first();

     
        $points = $audienceBrandPoint->points ?? 0;
        
        $allBadges = Badge::where('brand_id', $this->brandId)->get();

    //    dd($this->brandId, $points);
        [$currentBadge, $nextBadge] = (new GetAudienceCurrentAndNextBadge($this->brandId, $points))->run();

        $audienceBadgesList = (new GetAudienceBadgeListService($this->brandId, $audienceId, $points))->run();

        $rank = (new GetAudienceRankService($this->brandId, $audienceId,))->run();

        return ["point" => $points, "rank" => $rank, "current_badge" => $currentBadge, "next_badge" => $nextBadge,  "badges" => $allBadges , "audience_badges" => $audienceBadgesList, "audience_reward" => $audiencReward];

    }
}