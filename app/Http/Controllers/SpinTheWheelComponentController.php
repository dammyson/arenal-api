<?php

namespace App\Http\Controllers;

use App\Http\Requests\SpinTheWheel\StoreSpinTheWheelFormRequest;
use App\Http\Requests\SpinTheWheel\StoreSpinTheWheelButtonRequest;
use App\Http\Requests\SpinTheWheel\StoreSpinTheWheelSectorRequest;
use App\Http\Requests\SpinTheWheel\StoreSpinTheWheelSegmentRequest;
use App\Http\Requests\SpinTheWheel\StoreSpinTheWheelUserFormRequest;
use App\Http\Requests\SpinTheWheel\UpdateSpinTheWheelSegmentRequest;
use App\Services\SpinTheWheelService\StoreSpinTheWheelSectorService;
use App\Http\Requests\SpinTheWheel\StoreSpinTheWheelBackgroundRequest;
use App\Http\Requests\SpinTheWheel\StoreSpinTheWheelRewardSetupRequest;
use App\Http\Requests\SpinTheWheel\StoreSpinTheWheelSetUserFormRequest;
use App\Http\Requests\Game\SpinTheWheel\CreateSpinTheWheelSectorRequest;
use App\Http\Requests\SpinTheWheel\StoreSpinTheWheelAdsRequest;
use App\Http\Requests\SpinTheWheel\StoreSpinTheWheelCustomGameTextRequest;
use App\Http\Requests\SpinTheWheel\StoreSpinTheWheelParticipationRequest;
use App\Services\SpinTheWheelComponentService\StoreSpinTheWheelFormService;
use App\Services\SpinTheWheelComponentService\StoreSpinTheWheelButtonService;
use App\Services\SpinTheWheelComponentService\StoreSpinTheWheelSegmentService;
use App\Services\SpinTheWheelComponentService\DeleteSpinTheWheelSegmentService;
use App\Services\SpinTheWheelComponentService\StoreSpinTheWheelAdsService;
use App\Services\SpinTheWheelComponentService\StoreSpinTheWheelUserFormService;
use App\Services\SpinTheWheelComponentService\UpdateSpinTheWheelSegmentService;
use App\Services\SpinTheWheelComponentService\StoreSpinTheWheelBackgroundService;
use App\Services\SpinTheWheelComponentService\StoreSpinTheWheelCustomTextService;
use App\Services\SpinTheWheelComponentService\StoreSpinTheWheelParticipationService;
use App\Services\SpinTheWheelComponentService\StoreSpinTheWheelRewardSetupService;
use App\Services\SpinTheWheelComponentService\StoreSpinTheWheelSetUserFormService;

class SpinTheWheelComponentController extends BaseController
{

     public function storeSector(StoreSpinTheWheelSectorRequest $request) {
        try {
            $data = (new StoreSpinTheWheelSectorService($request))->run();

            return $this->sendResponse($data, "spin the wheel sector created successfully", 201);

        } catch (\Throwable $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);           
        }
    }


    public function storeSegment(StoreSpinTheWheelSegmentRequest $request) {
        try {
            $data = (new StoreSpinTheWheelSegmentService($request))->run();
            return $this->sendResponse($data, "spin the wheel segment created successfully", 201);

        } catch (\Throwable $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);           
        }

    }
    public function updateSegment(UpdateSpinTheWheelSegmentRequest $request, $id) {
        try {

            $data = (new UpdateSpinTheWheelSegmentService($request, $id))->run();
            return $this->sendResponse($data, "spin the wheel segment created successfully", 200);

        } catch (\Throwable $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);           
        }

    }
    public function deleteSegment($id) {
        try {

            $data = (new DeleteSpinTheWheelSegmentService($id))->run();
            return $this->sendResponse($data, "spin the wheel segment deleted successfully", 204);

        } catch (\Throwable $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);           
        }

    }

    public function storeButton(StoreSpinTheWheelButtonRequest $request) {
        try {
            $data = (new StoreSpinTheWheelButtonService($request))->run();
            return $this->sendResponse($data, "spin the wheel button created successfully", 201);

        } catch (\Throwable $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);           
        }

    }
    public function storeBackground(StoreSpinTheWheelBackgroundRequest $request) {
        try {
            
            $data = (new StoreSpinTheWheelBackgroundService($request))->run();
            return $this->sendResponse($data, "spin the wheel background created successfully", 201);

        } catch (\Throwable $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);           
        }

    }

    public function storeForm(StoreSpinTheWheelFormRequest $request) {
        try {
            $data = (new StoreSpinTheWheelFormService($request))->run();
            return $this->sendResponse($data, "spin the wheel form created successfully", 201);

        } catch (\Throwable $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);           
        }

    }

    public function setUserForm(StoreSpinTheWheelSetUserFormRequest $request) {
        try {
            $data = (new StoreSpinTheWheelSetUserFormService($request))->run();
            return $this->sendResponse($data, "spin the wheel user form created successfully", 201);

        } catch (\Throwable $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);           
        }

    }

    public function storeRewardSetup(StoreSpinTheWheelRewardSetupRequest $request) {
        try {
            $data = (new StoreSpinTheWheelRewardSetupService($request))->run();
            return $this->sendResponse($data, "spin the wheel reward setup created successfully", 201);

        } catch (\Throwable $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);           
        }

    }

    public function storeCustomText(StoreSpinTheWheelCustomGameTextRequest $request) {
        try {
            $data = (new StoreSpinTheWheelCustomTextService($request))->run();
            return $this->sendResponse($data, "spin the wheel custom text created successfully", 201);

        } catch (\Throwable $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);           
        }

    }

    public function participationFee(StoreSpinTheWheelParticipationRequest $request) {
        try {
            $data = (new StoreSpinTheWheelParticipationService($request))->run();
            return $this->sendResponse($data, "spin the wheel participation created successfully", 201);

        } catch (\Throwable $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);           
        }

    }

    public function ads(StoreSpinTheWheelAdsRequest $request) {
        try {
            $data = (new StoreSpinTheWheelAdsService($request))->run();
            return $this->sendResponse($data, "spin the wheel participation created successfully", 201);

        } catch (\Throwable $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);           
        }

    }
   
}
