<?php

namespace App\Services\Prize;

use App\Models\Prize;
use Illuminate\Http\Request;
use App\Models\AudienceBadge;
use App\Models\BrandAudienceReward;
use App\Services\BaseServiceInterface;

class GetBrandPrizeUserService implements BaseServiceInterface{
    protected $brandId;
    protected $request;

    public function __construct(Request $request, $brandId)
    {
        $this->brandId = $brandId;
        $this->request = $request;
    }

    public function run() {
        $audiencReward = BrandAudienceReward::where('brand_id', $this->brandId)
            ->where('audience_id', $this->request->user()->id)
            ->with('prize')
            ->get();


        $audienceBadges = AudienceBadge::where('brand_id', $this->brandId)
            ->where('audience_id', $this->request->user()->id)
            ->get();

        // if (!$prize) {
        //     return []
        // }

        return ["audience_reward" => $audiencReward, "audience_badges" => $audienceBadges];

    }
}