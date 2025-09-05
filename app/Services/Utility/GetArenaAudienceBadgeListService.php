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
    protected $audienceId;
    protected $points;
    public function __construct($audienceId, $points)
    {
        $this->audienceId = $audienceId;
        $this->points = $points;
        
    }

    public function run()
    {
        $arenaBadges = AudienceBadge::get();
            
        $audienceBadgesList = [];

        foreach ($arenaBadges as $brandBadge) {
            // dump($audienceBrandPoint->points, $brandBadge->points);
            if ($this->points >= $brandBadge->points) {
                ArenaAudienceBadges::firstOrCreate([
                    "audience_id" => $this->audienceId,
                    "arena_badge_id" => $brandBadge->id
                ]);
                $audienceBadgesList[] = $brandBadge;

            }
        }

        return $audienceBadgesList;

    }
    
}
