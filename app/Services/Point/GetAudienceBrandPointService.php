<?php

namespace App\Services\Point;

use App\Models\BrandPoint;
use Illuminate\Http\Request;
use App\Models\AudienceBadge;
use App\Models\CampaignGamePlay;
use App\Services\BaseServiceInterface;
use App\Http\Requests\Live\StoreJoinLiveRequest;

class GetAudienceBrandPointService implements BaseServiceInterface
{
    protected $request;
    protected $brandId;

    public function __construct(Request $request, $brandId)
    {
        $this->request = $request;
        $this->brandId = $brandId;
    }

    public function run()
    {
        try {

            $user = $this->request->user();
            $audiencePoints =  BrandPoint::where('brand_id', $this->brandId)
                ->where('audience_id', $user->id)
                ->first()
                ?->points ?? 0;

            
            $audienceBadgeCount =  AudienceBadge::where('brand_id', $this->brandId)
                ->where('audience_id', $user->id)
                ->count();

                
    
            $rank = $this->getUserRank($user->id, $this->brandId);
            $leaderboardCount = CampaignGamePlay::where("brand_id", $this->brandId)->count();
            // $rank = $this->getUserRank($user->id, $this->brandId, $campaignId, $this->gameId);

            return [
                'points' => $audiencePoints,
                'badge_count' => $audienceBadgeCount,
                "rank" => $rank,
                "leaderboard_count" => $leaderboardCount
            ];

                
                
        } catch (\Throwable $e) {

            throw $e;
        }
    }


    public function getUserRank($audienceId, $brandId)
    {
        // Get all user scores ordered by score descending
        $gamePlays = CampaignGamePlay::where('brand_id', $brandId)
            ->orderByDesc('score')
            ->pluck('audience_id'); // Just get audience IDs

        // Search for current audience position (add 1 for 1-based index)
        $rank = $gamePlays->search($audienceId);

        return $rank !== false ? $rank + 1 : null; // null if not found
    }
    
    // public function getUserRank($audienceId, $brandId, $campaignId, $gameId)
    // {
    //     // Get all user scores ordered by score descending
    //     $gamePlays = CampaignGamePlay::where('brand_id', $brandId)
    //         ->where('campaign_id', $campaignId)
    //         ->where('game_id', $gameId)
    //         ->orderByDesc('score')
    //         ->pluck('audience_id'); // Just get audience IDs

    //     // Search for current audience position (add 1 for 1-based index)
    //     $rank = $gamePlays->search($audienceId);

    //     return $rank !== false ? $rank + 1 : null; // null if not found
    // }
}
