<?php

namespace App\Services\Prize;

use App\Models\Prize;
use Illuminate\Http\Request;
use App\Models\AudienceBadge;
use App\Models\AudienceBadges;
use App\Models\BrandAudienceReward;
use App\Services\BaseServiceInterface;

class GetBrandAudienceBadgeService implements BaseServiceInterface{
    protected $brandId;
    protected $request;

    public function __construct(Request $request, $brandId)
    {
        $this->brandId = $brandId;
        $this->request = $request;
    }

    public function run() {
        $audienceBadges = AudienceBadge::where('brand_id', $this->brandId)
            ->where('audience_id', $this->request->user()->id)
            ->get();

        // if (!$prize) {
        //     return []
        // }

        return $audienceBadges;

    }
}