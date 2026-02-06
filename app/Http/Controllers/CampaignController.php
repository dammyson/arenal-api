<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use App\Http\Requests\Campaign\PlayCampaignGameRequest;
use Illuminate\Support\Facades\Gate;
use App\Services\Campaign\ShowCampaign;
use App\Services\Campaign\IndexCampaign;
use App\Services\Campaign\StoreCampaign;
use App\Services\CampaignGame\ShowCampaignGame;
use App\Http\Requests\Campaign\StoreCampaignRequest;
use App\Http\Requests\Campaign\StoreCampaignRewardRequest;
use App\Models\Airtime;
use App\Models\Audience;
use App\Models\BrandPoint;
use App\Models\CampaignCard;
use App\Models\CampaignCategory;
use App\Models\Cash;
use App\Models\Category;
use App\Models\Item;
use App\Models\Redemption;
use App\Models\Reward;
use App\Notifications\CampaignGameLink;
use App\Services\Utility\IndexUtils;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class CampaignController extends BaseController
{
    public function index(Request $request)
    {
        try {
        

            $data = (new IndexCampaign($request))->run();
        } catch (\Exception $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }
        return $this->sendResponse($data, "Campaign created succcessfully", 201);
    }

    public function createCampaignCategory(Request $request)
    {
        try {


            $cats = Category::create([
                'name' => "board",
            ]);           

            $campaigns = Campaign::get();

            foreach ($campaigns as $campaign) {
                $campaign->category_id = $cats->id;
                $campaign->save();
            }

        } catch (\Exception $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }
        return $this->sendResponse($campaigns, "Campaign created succcessfully", 201);
    }

    public function getCampaignCategory(Request $request)
    {
        try {

            $categorys = Category::get();           

          

        } catch (\Exception $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }
        return $this->sendResponse($categorys, "category retrieved succcessfully", 201);
    }

    public function fetchCampaigns($title)
    {
        try {

            $fetchedCampaign = Campaign::where('title', $title)->first();

            if (!$fetchedCampaign) {
                return response()->json(['error' => true, 'message' => 'Campaign not found'], 404);
            }

            return response()->json(['error' => false, 'data' => $fetchedCampaign->id], 200);
        } catch (\Exception $exception) {
            return response()->json(['error' => true, 'message' => $exception->getMessage()], 500);
        }
    }



    public function storeCampaign(StoreCampaignRequest $request)
    {
        try {

            $data = (new StoreCampaign($request))->run();
        } catch (\Exception $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }
        return $this->sendResponse($data, "Campaign created succcessfully", 201);
    }


    public function showCampaign($campaignId)
    {
        try {
            // dd(" iran");

            $isOpen = (new IndexUtils())->checkCampaignCapacity($campaignId);
            // dd($isOpen);
            if (!$isOpen) {
                return $this->sendError("Campaign Filled. Sorry you cant join this campaign", [], 500);
            }
            
            $data = (new ShowCampaign($campaignId))->run();
        } catch (\Exception $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }
        return $this->sendResponse($data, "Campaign retrieved succcessfully", 200);
    }

    public function storeCampaignCard(Request $request)
    {
        try {

            $campaignCard = CampaignCard::create([
                'campaign_id' => $request->campaign_id,
                'title' => $request->title,
                'description' => $request->description,
                'image_url' => $request->image_url,
                'link_text' => $request->link_text,
            ]);

        } catch (\Exception $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }
        return $this->sendResponse($campaignCard, "Campaign Card created succcessfully", 201);
    }

    public function getCampaignCards(Request $request, Campaign $campaign)
    {
        try {

            $campaignCards = CampaignCard::get();

        } catch (\Exception $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }
        return $this->sendResponse($campaignCards, "Campaign Card created succcessfully", 201);
    }

    public function startCampaign($campaignId)
    {
        try {

            $campaign = Campaign::find($campaignId);
            $campaign->status = "ACTIVE";
            $campaign->start_date = now();
            $campaign->save();
        } catch (\Exception $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }
        return $this->sendResponse($campaign, "Campaign started succcessfully", 200);
    }

    public function generateCampaignLink($campaignId, $gameId)
    {
        try {
            $campaign = Campaign::find($campaignId);
            $expired = now()->addHour(24);
            $user = auth()->user();

            $payload = "{$campaignId}|{$gameId}|{$user->id}";
            $encoded = base64_encode($payload);

            $url =  URL::temporarySignedRoute('play.game',  $expired, ['data' => $encoded]);
            $urlComponents = parse_url($url);
            $front_url = env('FRONT_END_URL', 24) . '?' . $urlComponents['query'];

            $user->notify(new CampaignGameLink($front_url));
        } catch (\Exception $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }
        return $this->sendResponse($front_url, "Campaign updated succcessfully", 200);
    }

    public function goToCampaignGame(PlayCampaignGameRequest $request)
    {
        try {

            $validated = $request->validated();

            if ($validated['is_link']) {
                if (!$request->hasValidSignature()) {
                    return response()->json([
                        'status'  => false,
                        'message' => 'Invalid/Expired link, contact admin'
                    ], 401);
                }
            }

            $gameId = $validated['game_id'];
            $campaignId = $validated['campaign_id'];

            if ($validated['is_link']) {
                $user = $validated['user_id'];
                $audience = Audience::where('id', auth()->user()->id)->firstOrFail();
                // $audience->is_invited == true
                $audience->user_id = $user;
                $audience->save();
            }

            $data = (new ShowCampaignGame($campaignId, $gameId))->run();
        } catch (ModelNotFoundException $e) {
            return $this->sendError("Resource not found", ['error' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }
        return $this->sendResponse($data, "Campaign Game retrieved succcessfully", 200);
    }

    public function createCampaignReward(StoreCampaignRewardRequest $request){
        $rewardType = $request->input('type');
        $campaign = Campaign::findOrFail($request->input('campaign_id'));

        if ($rewardType === 'cash') {
            $rewardMorph = Cash::create([                
                'image_url' => $request->input('image_url'),
                'amount_per_redemption' => $request->input('amount_per_redemption'),
            ]);

        } elseif ($rewardType === 'airtime') {
            $rewardMorph = Airtime::create([
                'airtime_value' => $request->input('airtime_value'),                
                'image_url' => $request->input('image_url'),
                'network' => $request->input('network'),
            ]);
         
        } elseif ($rewardType === 'item') {
            $rewardMorph = Item::create([
                'name' => $request->input('item_name'),
                'sku' => $request->input('item_sku'),
                'image_url' => $request->input('image_url'),
                'description' => $request->input('item_description')
            ]);           
        }

        $reward = $rewardMorph->reward()->create([
            'name' => $request->input('reward_name'),
            'campaign_id' => $campaign->id,
            'type' => $request->input('type'),
            'points_required' => $request->input('points_required'),
            'stock_total' => $request->input('stock_total'),
            'stock_remaining' => $request->input('stock_total'),
            'is_active' => $request->input('is_active', true),
        ]);

        return  $this->sendResponse($reward, "Campaign reward created successfully", 201);
    }

    public function getCampaignRewards(Campaign $campaign)
    {
        try {
            $rewards = $campaign->rewards()->get();
        } catch (\Exception $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }
        return $this->sendResponse($rewards, "Campaign rewards retrieved successfully", 200);
    }

    // Redeeming reward Logic 
    public function redeemReward(Request $request, $rewardId) {
        $user = $request->user();        
        
        $redemption = DB::transaction(function () use ($user, $rewardId) {

            $reward = Reward::where('id', $rewardId)
                ->lockForUpdate()
                ->first();

            if (!$reward) {
                throw new Exception('Reward not found');
            }

            $campaign = $reward->campaign()->first();            

            if (!$reward->is_active || $reward->isOutOfStock()) {
                throw new Exception('Reward unavailable');
            }

            $userPoint = BrandPoint::where('audience_id', $user->id)
                ->where('is_arena', true)
                // ->where('brand_id', $campaign->brand_id)
                ->first();

            // dd($userPoint);

            if ($userPoint->points < $reward->points_required) {
                throw new Exception('Insufficient points');
            }

            $userPoint->decrement('points', $reward->points_required);

            if (!is_null($reward->stock_remaining)) {
                $reward->decrement('stock_remaining');
            }

            return Redemption::create([
                'user_id' => $user->id,
                'reward_id' => $reward->id,
                'points_spent' => $reward->points_required,
            ]);
        });

        return $this->sendResponse($redemption, "Reward redeemed successfully", 200);

    }

    public function getRedemptions(Request $request)
    {
        try {
            $user = $request->user();
            $redemptions = Redemption::where('user_id', $user->id)->with('reward')->get();
        } catch (\Exception $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }
        return $this->sendResponse($redemptions, "User redemptions retrieved successfully", 200);
    }
}
