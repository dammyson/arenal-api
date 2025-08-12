<?php

namespace App\Services\Utility;

use App\Models\Badge;
use App\Models\BrandPoint;
use App\Models\AudienceBadge;
use App\Models\CampaignGamePlay;
use App\Services\BaseServiceInterface;

class GetAudienceBadgeListService implements BaseServiceInterface
{
    protected $brandId;
    protected $audienceId;
    protected $points;
    public function __construct($brandId, $audienceId, $points)
    {
        $this->brandId = $brandId;
        $this->audienceId = $audienceId;
        $this->points = $points;
        
    }

    public function run()
    {
        $brandBadges = Badge::where('brand_id', $this->brandId)->get();
        // dd($brandBadges);
            
        $audienceBadgesList = [];

        foreach ($brandBadges as $brandBadge) {
            // dump($audienceBrandPoint->points, $brandBadge->points);
            if ($this->points >= $brandBadge->points) {
                AudienceBadge::firstOrCreate([
                    "audience_id" => $this->audienceId,
                    "brand_id" => $this->brandId,
                    "badge_id" => $brandBadge->id
                ]);
                $audienceBadgesList[] = $brandBadge;

            }
        }

        return $audienceBadgesList;

    }
    
}
