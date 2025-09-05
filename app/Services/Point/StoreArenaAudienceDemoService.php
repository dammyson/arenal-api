<?php

namespace App\Services\Point;

use App\Http\Requests\DemographyRequest;
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
use App\Models\ArenaDemography;
use App\Services\Utility\GetAudienceRankService;
use App\Services\Utility\GetAudienceBadgeListService;
use App\Services\Utility\GetArenaAudienceBadgeListService;

class StoreArenaAudienceDemoService implements BaseServiceInterface
{
    protected $request;

    public function __construct(DemographyRequest $request)
    {
        $this->request = $request;
    }

    public function run()
    {
        try {

            $demo = ArenaDemography::create([...$this->request->validated(), "audience_id" => $this->request->user()->id]);

            return [
                // "user" => $user,
                "demography" => $demo
            ];  

                
                
        } catch (\Exception $e) {

            throw $e;
        }
    }

    
}
