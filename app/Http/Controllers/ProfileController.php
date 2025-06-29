<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeAudienceTransactionPin;
use App\Http\Requests\ChangePinRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use App\Services\Users\ProfileService;
use App\Http\Requests\ProfileEditRequest;
use App\Http\Requests\UploadImageRequest;
use App\Services\Images\UploadImageService;

class ProfileController extends BaseController
{   
    protected $profileService;
    public function __construct() {
        $user = Auth::user();
        $this->profileService = new ProfileService($user);
    }

    public function profile() 
    {
        try {
            $data = $this->profileService->getProfile();
        
        } catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "user profile retrieved succcessfully", 200);
   
    }


    public function editProfile(ProfileEditRequest $request) 
    {
        try {
            $data = $this->profileService->editProfile($request);


        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "user profile updated succcessfully", 200);
   
    }

    public function changePin(ChangeAudienceTransactionPin $request) 
    {
        try {
            $data = $this->profileService->changeAudiencePin($request->pin);


        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "user pin updated succcessfully", 200);
   
    }

    public function uploadImage(UploadImageRequest $request) 
    {
        try {
            $url = (new UploadImageService($request))->run();
            $data = $this->profileService->uploadProfilePhoto($url);


        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "user profile updated succcessfully", 200);
   
    }
    
    public function userInfo(Request $request)
    {
        try {   
            $data = $this->profileService->userInfo();

        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "user info retrieved succcessfully", 200);
   
    }

            
            
   
}
