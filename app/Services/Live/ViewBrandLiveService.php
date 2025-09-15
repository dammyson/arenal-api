<?php

namespace App\Services\Live;

use Carbon\Carbon;
use App\Models\Live;
use App\Models\Brand;
use App\Models\LiveTicket;
use App\Models\BrandDetail;
use App\Services\BaseServiceInterface;
use App\Http\Requests\Live\StoreLiveRequest;
use App\Http\Requests\User\BrandStoreRequest;

class ViewBrandLiveService implements BaseServiceInterface{
    protected $brandId;
    protected $branchId;

    public function __construct($brandId, $branchId = null)
    {
        $this->brandId = $brandId;
        $this->branchId = $branchId;
    }

    public function run() {
        try {

            if ($this->branchId) {
                $live = Live::where("branch_id", $this->branchId)->first();

            } else {

                $live = Live::where("brand_id", $this->brandId)->first();
            }

            if (!$live) {
                return [
                    "live" => null,
                    "live_count" => 0
                ];
            }


            $startDateTime = Carbon::today()->setTimeFromTimeString($live->start_time);
            $endDateTime = Carbon::today()->setTimeFromTimeString($live->end_time);
            
            $liveCount = LiveTicket::where("live_id", $live->id)->where("is_live", true)
                ->whereBetween('created_at', [$startDateTime, $endDateTime])
                ->count();
            

            return [
                "live" => $live, 
                "live_count" => $liveCount
            ];

        } catch(\Throwable $e) {
            
            throw $e;
        }
       
    }
}