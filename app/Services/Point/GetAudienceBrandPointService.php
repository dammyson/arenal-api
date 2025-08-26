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
use App\Models\AudienceLiveStreak;
use App\Models\Live;
use App\Services\Utility\GetAudienceRankService;
use App\Services\Utility\GetAudienceBadgeListService;

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

            $live = Live::where('brand_id', $this->brandId)->first();
            $liveStreak = AudienceLiveStreak::where('audience_id', $user->id)->where('live_id', $live->id)->first();
            $liveStreakCount = $liveStreak->streak_count ?? 0;

            $rank = (new GetAudienceRankService($this->brandId, $user->id))->run();
            $leaderboardCount = CampaignGamePlay::where("brand_id", $this->brandId)->count();
            $audienceBadgesList = (new GetAudienceBadgeListService( $this->brandId, $user->id, $audiencePoints))->run();


              
            $walletBalance = AudienceWallet::where('audience_id', $user->id)->first()?->balance ?? 0;


            return [
                // "user" => $user,
                "first_name" => $user->first_name,
                "last_name" => $user->last_name,
                "user_image" => $user->profile_image,
                "audience_badge_name" => $userBadge->name ?? "no badge yet",
                "live_duration" => $live->duration,
                'streak_count' => $liveStreakCount,
                'points' => $audiencePoints,
                'badge_count' => $audienceBadgeCount,
                "rank" => $rank,
                "leaderboard_count" => $leaderboardCount,
                "audience_badges" => $audienceBadgesList,
                "wallet_balance" => $walletBalance
            ];  

                
                
        } catch (\Exception $e) {

            throw $e;
        }
    }

    
}
