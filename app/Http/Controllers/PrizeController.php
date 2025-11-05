<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Prize;
use Illuminate\Http\Request;
use App\Models\BrandAudienceReward;
use App\Models\AudiencePrizeDelivery;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Arena\StoreArenaSpinTheWheelAudiencePrizeRequest;
use App\Services\Brand\StoreBrandService;
use App\Services\Prize\StorePrizeService;
use App\Services\Prize\GetBrandPrizeService;
use App\Services\Prize\RedeemHistoryService;
use App\Http\Requests\User\BrandStoreRequest;
use App\Http\Requests\User\PrizeStoreRequest;
use App\Services\Prize\GetBrandBadgesService;
use App\Services\Prize\GetBrandPrizeUserService;
use App\Http\Requests\Prize\PrizeDeliveryRequest;
use App\Services\Prize\RedeemUserBrandPrizeService;
use App\Services\Point\GetAudienceBrandPointService;
use App\Services\Prize\GetBrandAudienceBadgeService;
use App\Http\Requests\Prize\UpdatePrizeDeliveryRequest;
use App\Models\ArenaAudienceReward;
use App\Models\Badge;
use App\Models\SpinTheWheel;
use App\Notifications\ArenaRewardCode;
use App\Services\Achievement\AudienceBrandAchievementService;
use App\Services\Achievement\TestAudienceBrandAchievementService;

class PrizeController extends BaseController
{
    //

    public function storePrize(PrizeStoreRequest $request)
    {
        try {
            $data = (new StorePrizeService($request))->run();

        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "Prizes created succcessfully");
    }

   

    public function achievements(Request $request, Brand $brand)
    {
        try {
            // dd($brand);
            
            $data = (new AudienceBrandAchievementService($request, $brand->id))->run();

        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "Brand prize retrieved succcessfully");
    }

    public function arenaAchievements(Request $request)
    {
        try {
            // dd($brand);
            
            $data = (new TestAudienceBrandAchievementService($request))->run();

        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "Brand prize retrieved succcessfully");
    }

    // public function audienceBrandPrize(Request $request, Brand $brand)
    // {
    //     try {
    //         // dd($brand);
            
    //         $data = (new GetBrandPrizeUserService($request, $brand->id))->run();

    //     }  catch (\Exception $e){
    //         return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
    //     }        
    //     return $this->sendResponse($data, "Brand prize retrieved succcessfully");
    // }


    public function getAudiencePointBalance(Request $request, Brand $brand)
    {
        try {
            // dd($brand);
            
            $data = (new GetAudienceBrandPointService($request, $brand->id))->run();

        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "Brand info retrieved succcessfully");
    }

    public function redeemUserPrize(Request $request, $brandAudienceReward)
    {
        try {
            // "audience_id", "prize_id",
            // dd($brand);
            
            $data = (new RedeemUserBrandPrizeService($brandAudienceReward, $request->user()->id))->run();

        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "Brand info retrieved succcessfully");
    }


    public function redeemHistory(Request $request, Brand $brand)
    {
        try {
            
            $data = (new RedeemHistoryService($brand->id, $request->user()->id))->run();

        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "redeem History succcessfully");
    }


    public function getBrandAudienceBadges(Request $request, Brand $brand)
    {
        try {
            // dd($brand);
            
            $data = (new GetBrandAudienceBadgeService($request, $brand->id))->run();

        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "Brand info retrieved succcessfully");
    }

    public function getBrandBadges(Brand $brand)
    {
        try {            
            $data = (new GetBrandBadgesService($brand->id))->run();

        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "Brand info retrieved succcessfully");
    }

    public function getArenaBadges(Brand $brand)
    {
        try {            
            $badges = Badge::where('is_arena', true)
                ->get();

            $prizes = Prize::where('is_arena', true)
                ->get();

        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "Brand info retrieved succcessfully");
    }


    public function getBrandPrizes(Brand $brand)
    {
        try {            
            $data = (new GetBrandPrizeService($brand->id))->run();

        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "Brand info retrieved succcessfully");
    }

    public function getArenaPrizes()
    {
        try {            
            $data = Prize::where('is_arena', true)->get();

        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "Arena prizes retrieved succcessfully");
    }

    public function storeArenaAudiencesPrizes(StoreArenaSpinTheWheelAudiencePrizeRequest $request)
    {
        try {     
            $prizes = $request->validated()['prizes'];
            $audienceId = $request->user()->id;
                

            $data = [];
            foreach ($prizes as $prize) {
                $audienceReward = BrandAudienceReward::create([
                    'prize_id' => $prize['prize_id'],
                    'brand_id' => $prize['brand_id'],
                    'audience_id' => $audienceId,
                    'is_arena' => true,
                    'is_redeemed' => false
    
                ]);

                $data[] = $audienceReward;

            }

        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "Arena prizes retrieved succcessfully");
    }

    public function playSpinTheWheel(SpinTheWheel $spinTheWheel, StoreArenaSpinTheWheelAudiencePrizeRequest $request)
    {
        try {  
            $prizes = $request->validated()['prizes'];
            $audienceId = $request->user()->id;

            $gameId = $spinTheWheel->game_id;
                

            $data = [];

            foreach ($prizes as $prize) {
                $audienceReward =  ArenaAudienceReward::create([
                    'game_id' => $gameId,
                    'prize_name' => $prize,
                    'audience_id' => $audienceId,
                    'prize_code' => $this->generatePrizeCode(),
                    'is_redeemed' => false
    
                ]);
               

                $data[] = $audienceReward;

            }

        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "Arena prizes retrieved succcessfully");
    }

    public function getArenaReward(Request $request) {
        try {
            $audienceId = $request->user()->id;
            $data = ArenaAudienceReward::where('audience_id', $audienceId)->get();
            
            return $this->sendResponse($data, "Arena prizes retrieved succcessfully");

        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
   
    }

    public function redeemArenaReward(ArenaAudienceReward $reward, Request $request) {
        try {
            
            $user = $request->user();
            // dd($user->email);
            if ( $reward->is_redeemed) {
                return $this->sendError("Prize already redeemed", [], 400);

            }
            $reward->is_redeemed = true;
            $reward->save();
           

            $user->notify(new ArenaRewardCode( $reward->prize_name, $reward->prize_code));
           
            return $this->sendResponse($reward->prize_code, "{$reward->prize_name} redeemed successfully an Email as been sent with your reward code");

        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
   
    }

    private function generatePrizeCode($length = 8)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $referralId = '';
        for ($i = 0; $i < $length; $i++) {
            $referralId .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $referralId;
    }

    public function audienceBrandPrizeDelivery(BrandAudienceReward $brandAudienceReward, PrizeDeliveryRequest $prizeDeliveryRequest) {
        try {

            // dd($brandAudienceReward->id);
            $data = AudiencePrizeDelivery::create([
                ...$prizeDeliveryRequest->validated(),
                "brand_audience_reward_id" => $brandAudienceReward->id
            ]);

        }catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "Brand info retrieved succcessfully");

    }

    
    public function updateAudienceBrandPrizeDelivery(AudiencePrizeDelivery $audiencePrizeDelivery, UpdatePrizeDeliveryRequest $updatePrizeDeliveryRequest ) {
        try {
            $audiencePrizeDelivery->status = $updatePrizeDeliveryRequest["status"];
            $audiencePrizeDelivery->save();

        }catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($audiencePrizeDelivery, "Brand info retrieved succcessfully");

    }
    
    public function getAudienceBrandPrizeDelivery(AudiencePrizeDelivery $audiencePrizeDelivery) {
        try {
        //    dd("i ran");
            return $this->sendResponse($audiencePrizeDelivery, "Brand info retrieved succcessfully");

        }catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        

    }
}