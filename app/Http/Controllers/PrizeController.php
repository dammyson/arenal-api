<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Prize;
use Illuminate\Http\Request;
use App\Models\BrandAudienceReward;
use App\Models\AudiencePrizeDelivery;
use App\Http\Controllers\BaseController;
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
use App\Services\Achievement\AudienceBrandAchievementService;

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
        return $this->sendResponse($data, "Brand info retrieved succcessfully");
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


    public function getBrandPrizes(Brand $brand)
    {
        try {            
            $data = (new GetBrandPrizeService($brand->id))->run();

        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "Brand info retrieved succcessfully");
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