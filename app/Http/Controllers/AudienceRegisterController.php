<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\RegisterAudienceRequest;
use App\Models\Audience;
use App\Models\AudienceWallet;
use App\Models\Otp;
use Illuminate\Support\Facades\Validator;

class AudienceRegisterController extends BaseController
{
    public function registerAudience(RegisterAudienceRequest $request) {
        try {
            $data = $request->validated();

            $userData = [
                'is_audience' => true,
                'password' => bcrypt($data['password']),
            ];
            
            if (filter_var($data['email_or_phone'], FILTER_VALIDATE_EMAIL)) {
                $userData['email'] = $data['email_or_phone'];
            } else {
                $userData['phone_number'] = $data['email_or_phone'];
            }
            
            $audience = Audience::create($userData);
           
            $userWallet = AudienceWallet::create([
                'audience_id' => $audience->id,
                'revenue_share_group' => 'audience'
            ]);         
    
           
        } catch (\Exception $exception) {
            return response()->json(['error' => true, 'message' => $exception->getMessage()], 500);
        }
    
        $data['user'] =  $audience;
        $data['token'] =  $audience->createToken('Nova')->accessToken;
        $data['password'] = null;
    
        return response()->json([
            'error' => false, 
            'message' => 'Client registration successful. Verification code sent to your email.', 
            'data' => $data,
            'wallet' => $userWallet
        ], 201);
    }



    public function checkAudience(Request $request) {
        try {

            $validator = Validator::make($request->all(), [
                'email_or_phone' => ['required', function ($attribute, $value, $fail) {
                    if (!filter_var($value, FILTER_VALIDATE_EMAIL) &&
                        !preg_match('/^\+?[0-9]{10,15}$/', $value)) {
                        $fail('The '.$attribute.' must be a valid email address or phone number.');
                    }
                }],
            ]);
        
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
        
            $user = Audience::where('email', $request['email_or_phone'])
                ->orWhere('phone_number', $request['email_or_phone'])  
                ->first();

            if($user) {
                return $this->sendResponse($user, true, 200);

            } else {

                $otp = $this->generateFourDigitOtp();

                $otp = Otp::create([
                    'email_or_phone_no' => $request->email_or_phone,
                    'otp' => $otp
                ]);
                return $this->sendResponse($otp, false);
            }
        } catch(\Throwable $th) {

            return $this->sendError('something went wrong', $th->getMessage(), 500);
        }        

    }

    public function generateFourDigitOtp() {          
        $digits = 4;
        $otp= str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
        return $otp;
    }
    
}
