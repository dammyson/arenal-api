<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Services\Brand\IndexBrandService;
use App\Services\Brand\StoreBrandService;
use App\Services\Brand\DeleteBrandService;
use App\Services\Brand\UpdateBrandService;
use App\Http\Requests\User\StoreBrandBadges;
use App\Http\Requests\User\BrandStoreRequest;
use App\Http\Requests\User\BrandUpdateRequest;
use App\Services\Brand\StoreBrandBadgesService;
use App\Services\Point\GetAudienceBrandPointService;

class BrandController extends BaseController
{
    public function index(Request $request)
    {
        try {
            $data = (new IndexBrandService())->run();

        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }
        
        return $this->sendResponse($data, "Brand info retrieved succcessfully");
    
    
    }

    public function storeBrand(BrandStoreRequest $request)
    {
        try {
            $data = (new StoreBrandService($request))->run();

        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "Brand info retrieved succcessfully");
    }

    

    public function updateBrand(BrandUpdateRequest $request, $id) {
          try {
            $data = (new UpdateBrandService($request, $id))->run();

        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "Brand updated succcessfully");
    }

    public function getPoints(Request $request, Brand $brand) {
        try {

            $brandLive = (new GetAudienceBrandPointService($request, $brand->id))->run();
    
            return $this->sendResponse($brandLive, "audience points", 201);
        
        } catch (\Exception $e){

            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }   


    }





    public function userPrize(Request $request, Brand $brand) {
        try {

            $brandLive = (new GetAudienceBrandPointService($request, $brand->id))->run();
    
            return $this->sendResponse($brandLive, "live joined", 201);
        
        } catch (\Exception $e){

            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }   


    }

    
    public function storeBrandBadges(StoreBrandBadges $request) {
        try {
            
            $brandLive = (new StoreBrandBadgesService($request))->run();
    
            return $this->sendResponse($brandLive, "brand badges created successfully", 201);
        
        } catch (\Exception $e){

            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }   


    }


     public function deleteBrandBadges(Badge $badge) {
        try {
            
            $badge->delete();
            return $this->sendResponse("badge deleted successfully", "live joined", 201);
        
        } catch (\Exception $e){

            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }   


    }
    public function deleteBrand(Request $request, $id) {
        try {
            Gate::authorize('is-user');
            $data = (new DeleteBrandService($request, $id))->run();

        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "Brand delete succcessfully");
    }
}