<?php

namespace App\Services\Prize;

use App\Models\BrandPoint;
use Illuminate\Http\Request;
use App\Models\AudienceBadge;
use App\Models\BrandAudienceReward;
use App\Services\BaseServiceInterface;
use App\Services\Utility\GetAudienceRankService;
use App\Services\Utility\GetAudienceBadgeListService;

class GetBrandPrizeUserService implements BaseServiceInterface{
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


        $audienceBadges = AudienceBadge::where('brand_id', $this->brandId)
            ->where('audience_id', $audienceId)
            ->get();


        $audienceBrandPoint = BrandPoint::where('brand_id', $this->brandId)        
            ->where("audience_id", $audienceId)
            ->first();

     
        $points = $audienceBrandPoint->points ?? 0;

        $audienceBadgesList = (new GetAudienceBadgeListService( $this->brandId, $audienceId, $points))->run();

        $rank = (new GetAudienceRankService($this->brandId, $audienceId,))->run();
        // if (!$prize) {
        //     return []
        // }

        return ["point" => $points, "rank" => $rank, "badges" => $audienceBadgesList , "audience_reward" => $audiencReward, "audience_badges" => $audienceBadges];

    }
}