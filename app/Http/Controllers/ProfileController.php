<?php

namespace App\Http\Controllers;

use App\Models\Otp;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Audience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Notifications\ArenaForgotPin;
use App\Services\Users\ProfileService;
use App\Http\Requests\ChangePinRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProfileEditRequest;
use App\Http\Requests\UploadImageRequest;

use App\Services\Images\UploadImageService;
use Illuminate\Support\Facades\Notification;
use App\Http\Requests\ChangeAudienceTransactionPin;
use App\Http\Requests\ForgotAudienceTransactionPin;
use App\Http\Requests\SetAudienceTransactionPinRequest;

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

    public function setPin(SetAudienceTransactionPinRequest $request) 
    {
        try {
            $data = $this->profileService->setPin($request->pin);


        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "user pin updated succcessfully", 200);
   
    }

    public function changePin(ChangeAudienceTransactionPin $request) 
    {
        try {
            $user = $request->user();
            $data = $this->profileService->changePin($request->pin, $user->pin, $request->old_pin);


        }  catch (\Exception $e){
            return $this->sendError($e->getMessage(), ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "user pin updated succcessfully", 200);
   
    }

    public function forgotPin(ForgotAudienceTransactionPin $request) 
    {
        try {
            $newPin = $request->input("new_pin");
            $user = $request->user();

          
            
            $data = $this->profileService->setPin($newPin);

            return $this->sendResponse("pin changed successfully", 'pin changed successfully');
           

        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse($data, "user pin updated succcessfully", 200);
   
    }

    public function getOTP(Request $request) 
    {
        try {

            $user = $request->user();
            
            if (!$user) {
                return $this->sendError("user does not exist");
            }

            $otpCode = $this->generateFourDigitOtp();

            if ($user->email) {
                  
                Otp::create([
                    'email_or_phone_no' => $user->email,
                    'otp' => $otpCode
                ]);

                Notification::route('mail', $user->email)
                    ->notify(new ArenaForgotPin($otpCode));
            } else {

                Otp::create([
                    'email_or_phone_no' => $user->phone_number,
                    'otp' => $otpCode
                ]);

                Notification::route('nexmo', $user->phone_number) // or 'vonage' depending on your SMS driver
                    ->notify(new ArenaForgotPin($otpCode));
            }

            return $this->sendResponse($otpCode, "otp sent succcessfully", 200);

        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
   
    }

    public function validateOtp(Request $request) 
    {
        try {

            $user = $request->user();
            $user = $request->user();

            $otp = Otp::where('otp', $request['otp'])
                ->where('email_or_phone_no', $user->email)
                ->Orwhere('email_or_phone_no', $user->phone_number)
                ->where('created_at', '<' , now()->subMinutes(10))->first();


            if (!$otp) {
                return $this->sendError('wrong otp', null, 400);

            }
            
            if ($otp->is_verified) {
                return $this->sendError('otp already verified', null, 400);
            }

            $otp->is_verified = true;
            $otp->save();

            return $this->sendResponse("success", "Otp verified succesfully", 200);

        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
   
    }

     
    public function generateFourDigitOtp()
    {
        $digits = 4;
        $otp = str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
        return $otp;
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
