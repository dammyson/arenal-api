<?php

namespace App\Http\Controllers;

use App\Models\Live;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Services\Live\JoinBrandLiveService;
use App\Services\Live\ViewBrandLiveService;
use App\Http\Requests\Live\StoreLiveRequest;
use App\Services\Live\StoreBrandLiveService;
use App\Services\Live\UpdateBrandLiveService;
use App\Http\Requests\Live\StoreJoinLiveRequest;
use App\Http\Requests\Live\UpdateBrandLiveRequest;
use App\Http\Requests\Live\UpdateStoreLiveRequest;
use App\Services\Point\GetAudienceBrandPointService;

class LiveController extends BaseController
{
    public function storeBrandLive(StoreLiveRequest $request) {
        try {
            $brandLive = (new StoreBrandLiveService($request))->run();
    
            return $this->sendResponse($brandLive, "live brand saved successfully", 201);
        
        } catch (\Exception $e){

            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }   


    }

    public function updateBrandLive(UpdateBrandLiveRequest $request, Live $live) {
        try {
            $updatedBrandLive = (new UpdateBrandLiveService($live, $request))->run();
    
            return $this->sendResponse($updatedBrandLive, "live brand updated successfully", 201);
        
        } catch (\Exception $e){

            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }   


    }

    public function viewBrandLive(Brand $brand) {
        try {

            $brandLive = (new ViewBrandLiveService($brand->id))->run();
    
            return $this->sendResponse($brandLive, "live brand retrieved successfully");
        
        } catch (\Exception $e){

            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }   


    }


    public function joinLive(StoreJoinLiveRequest $request) {
        try {

            $brandLive = (new JoinBrandLiveService($request))->run();
    
            return $this->sendResponse($brandLive, "live joined", 201);
        
        } catch (\Exception $e){

            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }   


    }
   
}
