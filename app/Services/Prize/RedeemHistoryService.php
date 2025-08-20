<?php

namespace App\Services\Prize;

use App\Models\BrandAudienceReward;
use App\Services\BaseServiceInterface;
use App\Models\Prize;
use Illuminate\Http\Request;

class RedeemHistoryService implements BaseServiceInterface{
    protected $brandId;
    protected $userId;

    public function __construct($brandId, $userId)
    {
        $this->brandId = $brandId;        
        $this->userId = $userId;        
    }

    public function run() {
        
        return BrandAudienceReward::where("brand_id", $this->brandId)
            ->where("audience_id", $this->userId)
            ->where('is_redeemed', true)
            ->with('prize')
            ->get();

      

    }
}