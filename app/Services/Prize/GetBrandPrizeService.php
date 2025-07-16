<?php

namespace App\Services\Prize;

use App\Services\BaseServiceInterface;
use App\Models\Prize;

class GetBrandPrizeService implements BaseServiceInterface{
    protected $brandId;

    public function __construct( $brandId)
    {
        $this->brandId = $brandId;
        
    }

    public function run() {

        $prize = Prize::where('brand_id', $this->brandId)->get();

        return $prize;

    }
}