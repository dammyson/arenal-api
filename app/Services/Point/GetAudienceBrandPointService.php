<?php

namespace App\Services\Point;

use App\Models\Badge;
use App\Models\BrandPoint;
use Illuminate\Http\Request;
use App\Models\AudienceBadge;
use App\Models\AudienceWallet;
use App\Models\CampaignGamePlay;
use App\Services\BaseServiceInterface;
use App\Services\Utility\GetUserRankService;
use App\Http\Requests\Live\StoreJoinLiveRequest;
use App\Services\Utility\GetAudienceRankService;

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

            
            $userBadge = Badge::where("points", "<=", $audiencePoints)->orderBy("points", "desc")->first();


            $rank = (new GetAudienceRankService($this->brandId, $user->id))->run();
            $leaderboardCount = CampaignGamePlay::where("brand_id", $this->brandId)->count();

              
            $walletBalance = AudienceWallet::where('audience_id', $user->id)->first()?->balance ?? 0;


            return [
                "audience_badge_name" => $userBadge->name ?? "no badge yet",
                'points' => $audiencePoints,
                'badge_count' => $audienceBadgeCount,
                "rank" => $rank,
                "leaderboard_count" => $leaderboardCount,
                "wallet_balance" => $walletBalance
            ];

                
                
        } catch (\Throwable $e) {

            throw $e;
        }
    }

    
}
