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
use App\Models\Audience;
use App\Models\CampaignCard;
use App\Notifications\CampaignGameLink;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
}
