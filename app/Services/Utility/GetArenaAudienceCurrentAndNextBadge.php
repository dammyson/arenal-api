<?php

namespace App\Services\Utility;

use App\Models\ArenaBadges;
use App\Models\Badge;
use App\Models\BrandPoint;
use App\Models\AudienceBadge;
use App\Models\CampaignGamePlay;
use App\Services\BaseServiceInterface;

class GetArenaAudienceCurrentAndNextBadge implements BaseServiceInterface
{
    protected $points;
    public function __construct( $points)
    {
        $this->points = $points;
        
    }

    public function run()
    {

        // dd(" i got here");
        $currentBadge = ArenaBadges::where('points', '<=', $this->points)
            ->orderBy('points', 'desc')
            ->first();


        $nextBadge = ArenaBadges::where('points', '>', $this->points)
            ->orderBy('points', 'asc')
            ->first();


        return [$currentBadge, $nextBadge];

    }
    
}
