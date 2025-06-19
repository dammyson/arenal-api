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

}


// for spin the wheel mechanism we have
//  backgound_gradient background_color and background_image, start_time
//  button_color, button_solid_style, button_outline_style, button_3d_styles, button_custom_png, has_custom_png
//  interactive_component

//  SpinTheWheelSectorSegment (BelongsTo spintheWheelSector)
//  label_text, label_color, background_color, icon, probability

//  sectorform (BelongsTo spinThewheeSector)
//     title, 
//     description, 
//     text_style,
//     user_name, user_email, phone_number, is_marked_filed as required(note front end might take care of this one), button text


//  sectorRewardSetup (BelongsTo spinTheWheelSector)
//     reward_name
//     limit_setting
//     deliveryMethod
//     custom_success_message
//     custom_button

//  Text (BelongsTo spinTheWheelSector)
//     game_title
//     description
//     error_message
//     style


