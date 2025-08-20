<?php

namespace App\Services\Utility;

use App\Models\Badge;
use App\Models\BrandPoint;
use App\Models\AudienceBadge;
use App\Models\CampaignGamePlay;
use App\Services\BaseServiceInterface;

class GetAudienceCurrentAndNextBadge implements BaseServiceInterface
{
    protected $brandId;
    protected $points;
    public function __construct($brandId,  $points)
    {
        $this->brandId = $brandId;
        $this->points = $points;
        
    }

    public function run()
    {

        // dd(" i got here");
        $currentBadge = Badge::where('brand_id', $this->brandId)
            ->where('points', '<=', $this->points)
            ->orderBy('points', 'desc')
            ->first();


        $nextBadge = Badge::where('brand_id', $this->brandId)
            ->where('points', '>', $this->points)
            ->orderBy('points', 'asc')
            ->first();


        return [$currentBadge , $nextBadge];

    }
    
}
