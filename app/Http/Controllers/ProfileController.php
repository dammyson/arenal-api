<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\Users\ProfileService;
use App\Http\Requests\ProfileEditRequest;

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
