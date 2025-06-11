<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Services\Campaign\ShowCampaign;
use App\Services\Campaign\IndexCampaign;
use App\Services\Campaign\StoreCampaign;
use App\Services\CampaignGame\ShowCampaignGame;
use App\Http\Requests\Campaign\StoreCampaignRequest;
use App\Models\Audience;
use App\Notifications\CampaignGameLink;

class CampaignController extends BaseController
{
    public function index()
    {
        try {

            $data = (new IndexCampaign())->run();
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

            $data = (new ShowCampaign($campaignId))->run();
        } catch (\Exception $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }
        return $this->sendResponse($data, "Campaign retrieved succcessfully", 200);
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

    public function goToCampaignGame(Request $request)
    {
        try {

            if (!$request->hasValidSignature()) {
                return response()->json(['status' => false, 'message' => 'Invalid/Expired link, contact admin'], 401);
            }

            $decodedPayload = explode('|', base64_decode($request->query('data')));
            $campaignId = $decodedPayload[0];
            $gameId = $decodedPayload[1];
            $user = $decodedPayload[2];

            $audience=  Audience::where('id', auth()->user()->id)->firstOrFail();
            $audience->user_id = $user;
            $audience->save();
          
            $data = (new ShowCampaignGame($campaignId, $gameId))->run();
        } catch (\Exception $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }
        return $this->sendResponse($data, "Campaign Game retrieved succcessfully", 200);
    }

    
}
