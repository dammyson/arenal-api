<?php

namespace App\Services\Utility;

use App\Models\ArenaAudienceBadges;
use App\Models\Badge;
use App\Models\BrandPoint;
use App\Models\AudienceBadge;
use App\Models\CampaignGamePlay;
use App\Services\BaseServiceInterface;

class GetArenaAudienceBadgeListService implements BaseServiceInterface
{
    protected $brandId;
    protected $audienceId;
    protected $points;
    protected $isArena;
    public function __construct($brandId, $audienceId, $points, $isArena)
    {
        $this->brandId = $brandId;
        $this->audienceId = $audienceId;
        $this->points = $points;
        $this->isArena = $isArena;
        
    }

    public function run()
    {   

       $brandBadges = $this->isArena ? Badge::where('is_arena', $this->isArena)->get() :  Badge::where('brand_id', $this->brandId)->get();
            
        $audienceBadgesList = [];

        foreach ($brandBadges as $brandBadge) {
            // dump($audienceBrandPoint->points, $brandBadge->points);
            if ($this->points >= $brandBadge->points) {
                AudienceBadge::firstOrCreate([
                    "audience_id" => $this->audienceId,
                    "brand_id" => $this->brandId,
                    "is_arena" => $this->isArena,
                    "badge_id" => $brandBadge->id
                ]);
                $audienceBadgesList[] = $brandBadge;

            }
        }

        


        return $audienceBadgesList;

    }
    
}
