<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Prize;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Services\Brand\StoreBrandService;
use App\Services\Prize\StorePrizeService;
use App\Services\Prize\GetBrandPrizeService;
use App\Http\Requests\User\BrandStoreRequest;
use App\Http\Requests\User\PrizeStoreRequest;
use App\Services\Prize\GetBrandBadgesService;
use App\Services\Prize\GetBrandPrizeUserService;
use App\Services\Prize\RedeemUserBrandPrizeService;
use App\Services\Point\GetAudienceBrandPointService;
use App\Services\Prize\GetBrandAudienceBadgeService;

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

   

    public function audienceBrandPrize(Request $request, Brand $brand)
    {
        try {
            // dd($brand);
            
            $data = (new GetBrandPrizeUserService($request, $brand->id))->run();

        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "Brand prize retrieved succcessfully");
    }


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
}