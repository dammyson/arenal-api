<?php

namespace App\Services\Prize;

use App\Models\BrandAudienceReward;
use App\Services\BaseServiceInterface;
use App\Models\Prize;
use Illuminate\Http\Request;

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

        // if (!$prize) {
        //     return []
        // }

        return $audiencReward;

    }
}