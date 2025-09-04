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
        // $gamePlays = CampaignGamePlay::where('brand_id', $this->brandId)
        //     ->orderByDesc('score')
        //     ->pluck('audience_id'); // Just get audience IDs

        $gamePlays = CampaignGamePlay::where('brand_id', $this->brandId)
            ->select('audience_id')
            ->selectRaw('MAX(score) as max_score')
            ->groupBy('audience_id')
            ->orderByDesc('max_score')
            ->pluck('audience_id');

        // Search for current audience position (add 1 for 1-based index)
        $rank = $gamePlays->search($this->audienceId);

        // dd($rank);

        return $rank !== false ? $rank + 1 : null; // null if not found
    }
    
}
