<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Http\Requests\SpinTheWheel\StoreSpinTheWheelRequest;
use App\Services\SpinTheWheelService\showSpinTheWheelService;
use App\Services\SpinTheWheelService\IndexSpinTheWheelService;
use App\Services\SpinTheWheelService\StoreSpinTheWheelService;
use App\Services\SpinTheWheelService\AttachSpinToSectorService;
use App\Http\Requests\Game\SpinTheWheel\CreateSpinTheWheelRequest;
use App\Http\Requests\SpinTheWheel\StoreSpinTheWheelSectorRequest;
use App\Services\SpinTheWheelService\IndexUserSpinTheWheelService;
use App\Services\SpinTheWheelService\StoreSpinTheWheelSectorService;
use App\Http\Requests\Game\SpinTheWheel\CreateSpinTheWheelSectorRequest;
use App\Http\Requests\SpinTheWheel\StoreSpinTheWheelAudienceRewardRequest;
use App\Models\SpinTheWheel;
use App\Services\SpinTheWheelService\StoreSpinTheWheelAudienceRewardService;

class SpinTheWheelController extends BaseController
{
    public function store(StoreSpinTheWheelRequest $request) {
        
        try {
            $data = (new StoreSpinTheWheelService($request))->run();

            return $this->sendResponse($data, "spin the wheel created successfully", 201);

        } catch (\Throwable $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);           
        }
        
    }
    public function Update(StoreSpinTheWheelRequest $request) {
        
        try {
            $data = (new StoreSpinTheWheelService($request))->run();

            return $this->sendResponse($data, "spin the wheel created successfully", 201);

        } catch (\Throwable $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);           
        }
        
    }
    
    public function show($id) {
        
        try {
            $data= (new showSpinTheWheelService($id))->run();

            return $this->sendResponse($data, "data retrieved successfully");

        } catch (\Throwable $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);           
        }        
    }

    public function userIndex(Request $request) {
        
        try {
            $data = (new IndexUserSpinTheWheelService($request))->run();

            return $this->sendResponse($data, "data retrieved successfully");

        } catch (\Throwable $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);           
        }        
    }

    public function index() {
        try {  
            $data = (new IndexSpinTheWheelService())->run();

            return $this->sendResponse($data, "data retrieved successfully");

        } catch (\Throwable $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);           
        }        
    }

    public function storeSpinSectorOnly(StoreSpinTheWheelSectorRequest $request) {
        try {
            $data = (new StoreSpinTheWheelSectorService($request))->run();

            return $this->sendResponse($data, "spin the wheel created successfully", 201);

        } catch (\Throwable $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);           
        }
    }

   

    public function attachSpinAndSector($spinId, $spinSectorId) {
        try {
            $data = (new AttachSpinToSectorService($spinId, $spinSectorId))->run();

            return $this->sendResponse($data, "spin the wheel created successfully", 201);

        } catch (\Throwable $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);           
        }
    }

    public function audiencePrize(StoreSpinTheWheelAudienceRewardRequest $request) {
        try {
            $data = (new StoreSpinTheWheelAudienceRewardService($request))->run();

            return $this->sendResponse($data, "audience prize stored successfully", 201);

        } catch (\Throwable $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);           
        }
    }

    public function getSpinTheWheelDetails(SpinTheWheel $spinTheWheel, Request $request) {
        $details = $spinTheWheel->load([
            'spinTheWheelAds', 
            'spinTheWheelSegments', 
            'spinTheWheelBackground',  
            'spinTheWheelParticipationDetails',
            'showUserForm',
            'spinTheWheelCustomGameTexts',
            // 'spinTheWheelRewardSetups',
            'spinTheWheelUserForms',
            'spinTheWheelForms',
        ]);

        return $details;
    }

}


