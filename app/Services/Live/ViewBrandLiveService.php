<?php

namespace App\Services\Live;

use App\Models\Live;
use App\Models\Brand;
use App\Models\BrandDetail;
use App\Services\BaseServiceInterface;
use App\Http\Requests\Live\StoreLiveRequest;
use App\Http\Requests\User\BrandStoreRequest;

class ViewBrandLiveService implements BaseServiceInterface{
    protected $brandId;

    public function __construct($brandId)
    {
        $this->brandId = $brandId;
    }

    public function run() {
        try {
            $live = Live::where("brand_id", $this->brandId)->get();

            return $live;

        } catch(\Throwable $e) {
            
            throw $e;
        }
       
    }
}