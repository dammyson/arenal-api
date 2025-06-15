<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
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

            
            
     public function uploadImages(Request $request)
    {
        $validateRequest = $request->validate([
            'image' => ['required', 'image', 'max:2048'],
        ]);

        $file = $request->file('image');
        $path = Storage::disk('cloudinary')->putFile('uploads', $file);
        $url = Storage::disk('cloudinary')->url($path);

        
        $this->activityLoggerService
            ->setLogName('images')
            ->setDescription("user with id {$request->user()->id} uploaded images")
            ->setEvent('upload')
            ->log();

        return response()->json([
            "url"=> $url,
        ]);
    }
}
