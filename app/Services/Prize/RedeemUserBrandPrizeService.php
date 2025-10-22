<?php

namespace App\Services\Prize;

use App\Models\BrandAudienceReward;
use App\Services\BaseServiceInterface;
use App\Models\Prize;
use Illuminate\Http\Request;

class RedeemUserBrandPrizeService implements BaseServiceInterface{
    protected $brandAudienceReward;
    protected $userId;

    public function __construct($brandAudienceReward, $userId)
    {
        $this->brandAudienceReward = $brandAudienceReward;        
        $this->userId = $userId;        
    }

    public function run() {
        $audienceReward = BrandAudienceReward::where("id", $this->brandAudienceReward)
            ->where("audience_id", $this->userId)
            ->with('prize')  
            ->first();

        if ($audienceReward) {
            $audienceReward->is_redeemed = true;
            $audienceReward->save();
            return $audienceReward;

        }


        return "not found";

    }
}