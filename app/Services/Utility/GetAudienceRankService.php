<?php

namespace App\Services\Utility;

use App\Models\CampaignGamePlay;
use App\Services\BaseServiceInterface;

class GetAudienceRankService implements BaseServiceInterface
{
    protected $brandId;
    protected $audienceId;
    public function __construct($brandId, $audienceId)
    {
        $this->brandId = $brandId;
        $this->audienceId = $audienceId;
        
    }

    public function run()
    {
         // Get all user scores ordered by score descending
        $gamePlays = CampaignGamePlay::where('brand_id', $this->brandId)
            ->orderByDesc('score')
            ->pluck('audience_id'); // Just get audience IDs

        // Search for current audience position (add 1 for 1-based index)
        $rank = $gamePlays->search($this->audienceId);

        return $rank !== false ? $rank + 1 : null; // null if not found
    }
    
}
