<?php

namespace App\Services\Prize;

use App\Models\Badge;
use App\Services\BaseServiceInterface;
use App\Models\Prize;
use Illuminate\Http\Request;


class GetBrandBadgesService implements BaseServiceInterface{
    protected $brandId;

    public function __construct($brandId)
    {
        $this->brandId = $brandId;
    }

    public function run() {
        
        $badge = Badge::where('brand_id', $this->brandId)
            ->get();

        return $badge;

    }
}