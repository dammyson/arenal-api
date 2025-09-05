<?php

namespace App\Services\Point;

use App\Models\Live;
use App\Models\Badge;
use App\Models\BrandPoint;
use App\Models\ArenaBadges;
use Illuminate\Http\Request;
use App\Models\AudienceBadge;
use App\Models\AudienceWallet;
use App\Models\CampaignGamePlay;
use App\Models\AudienceLiveStreak;
use App\Models\ArenaAudienceBadges;
use App\Services\BaseServiceInterface;
use App\Services\Utility\GetUserRankService;
use App\Http\Requests\Live\StoreJoinLiveRequest;
use App\Services\Utility\GetAudienceRankService;
use App\Services\Utility\GetAudienceBadgeListService;
use App\Services\Utility\GetArenaAudienceBadgeListService;

class GetArenaAudienceProfileService implements BaseServiceInterface
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function run()
    {
        try {

            $user = $this->request->user();
            $audiencePoints =  CampaignGamePlay::where('brand_id', null)
                ->where('audience_id', $user->id)
                ->first()
                ?->score ?? 0;
            
            $audienceBadgeCount =  ArenaAudienceBadges::where('audience_id', $user->id)
                ->count(); 

            
            $userBadge = ArenaBadges::where("points", "<=", $audiencePoints)->orderBy("points", "desc")->first();


            $rank = (new GetAudienceRankService(null, $user->id))->run();
            $leaderboardCount = CampaignGamePlay::where("brand_id", null)->count();
            // dd(" ig ot here");
            $audienceBadgesList = (new GetArenaAudienceBadgeListService($user->id, $audiencePoints))->run();


              
            $walletBalance = AudienceWallet::where('audience_id', $user->id)->first()?->balance ?? 0;


            return [
                // "user" => $user,
                "first_name" => $user->first_name,
                "last_name" => $user->last_name,
                "user_image" => $user->profile_image,
                "audience_badge_name" => $userBadge->name ?? "no badge yet",
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
