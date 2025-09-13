<?php

namespace App\Services\Utility;

use App\Models\Badge;
use App\Models\BrandPoint;
use App\Models\AudienceBadge;
use App\Models\CampaignGamePlay;
use App\Services\BaseServiceInterface;

class GetTestAudienceCurrentAndNextBadge implements BaseServiceInterface
{
    protected $brandId;
    protected $points;
    protected $isArena;
    public function __construct($brandId, $points, $isArena )
    {
        $this->brandId = $brandId;
        $this->points = $points;
        $this->isArena = $isArena;
        
    }

    public function run()
    {

        // dd(" i got here");
        if ($this->isArena == true) {
            $currentBadge = Badge::where('is_arena', $this->isArena)
                ->where('points', '<=', $this->points)
                ->orderBy('points', 'desc')
                ->first();


            $nextBadge = Badge::where('is_arena', $this->isArena)
                ->where('points', '>', $this->points)
                ->orderBy('points', 'asc')
                ->first();

        } else {
            $currentBadge = Badge::where('brand_id', $this->brandId)
                ->where('is_arena', $this->isArena)
                ->where('points', '<=', $this->points)
                ->orderBy('points', 'desc')
                ->first();


            $nextBadge = Badge::where('brand_id', $this->brandId)
                ->where('is_arena', $this->isArena)
                ->where('points', '>', $this->points)
                ->orderBy('points', 'asc')
                ->first();
        }

        return [$currentBadge, $nextBadge];

    }
    
}
