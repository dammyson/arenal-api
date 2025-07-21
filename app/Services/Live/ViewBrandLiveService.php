<?php

namespace App\Services\Live;

use App\Models\Live;
use App\Models\Brand;
use App\Models\BrandDetail;
use App\Services\BaseServiceInterface;
use App\Http\Requests\Live\StoreLiveRequest;
use App\Http\Requests\User\BrandStoreRequest;
use App\Models\LiveTicket;

class ViewBrandLiveService implements BaseServiceInterface{
    protected $brandId;

    public function __construct($brandId)
    {
        $this->brandId = $brandId;
    }

    public function run() {
        try {
            $live = Live::where("brand_id", $this->brandId)->first();

            $liveCount = LiveTicket::where("live_id", $live->id)->where("is_live", true)->count();

            return [
                "live" => $live, 
                "live_count" => $liveCount
            ];

        } catch(\Throwable $e) {
            
            throw $e;
        }
       
    }
}