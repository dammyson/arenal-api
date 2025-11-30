<?php

namespace App\Services\Utility;

use App\Models\CampaignGamePlay;
use App\Services\BaseServiceInterface;

class GetAudienceRankService implements BaseServiceInterface
{
    protected $brandId;
    protected $audienceId;
    protected $isArena;
    public function __construct($brandId, $audienceId, $isArena = false)
    {
        $this->brandId = $brandId;
        $this->audienceId = $audienceId;
        $this->isArena = $isArena;
        
    }

    public function run()
    {
         // Get all user scores ordered by score descending
      
        $gamePlays = CampaignGamePlay::when($this->isArena, fn($q) => $q->where('is_arena', true))
            ->when((!$this->isArena), fn($q) => $q->where('brand_id', $this->brandId))
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
