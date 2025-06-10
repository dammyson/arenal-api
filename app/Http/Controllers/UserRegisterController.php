<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Otp;
use App\Models\User;
use App\Models\Company;
use App\Models\CompanyUser;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use App\Services\Users\CreateUserService;
use App\Services\Company\CreateCompanyService;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Requests\Auth\RegisterAudienceRequest;
use App\Http\Requests\Auth\UserLoginRequest;

class UserRegisterController extends BaseController
{
  public function userRegister(RegisterUserRequest $request)
{
    try {
        $validated = $request->validated();

        $user = (new CreateUserService($validated))->run();
        if (!$user) {
            return $this->sendError(
                "User creation failed",
                [],
                500
            );
        }

        $companyData = (new CreateCompanyService($validated, $user->id))->run();
        if (!$companyData) {
            return $this->sendError(
                "Company creation failed",
                [],
                500
            );
        }

        $data = [
            'user' => $user,
            'company' => $companyData['company'] ?? null,
            'company_user' => $companyData['companyUser'] ?? null,
            'token' => $user->createToken('Nova')->accessToken,
        ];

        return $this->sendResponse($data, 'User registration successful', 201);

    } catch (\Exception $exception) {
        // Optionally, log the exception: \Log::error($exception);
        return $this->sendError(
            "Something went wrong",
            ['error' => $exception->getMessage()],
            500
        );
    }
}


    public function generateFourDigitOtp() {          
        $digits = 4;
        $otp= str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
        return $otp;
    }

    public function verifyOtp(Request $request) {
        
        try {
            $otp = Otp::where('email_or_phone_no', $request['email_or_phone_no'])->where('otp', $request['otp'])->first();

            if ($otp->is_verified) {
                return $this->sendError('otp already verified', null, 400);
            }

            if ($otp) {
                $otp->is_verified = true;
                $otp->save();
                return $this->sendResponse($otp, 'otp verifcation successfully');
            
            } else {
                return $this->sendError($otp, 'otp verification failed', 422);
            }
            
        } catch(\Throwable $th) {

            return $this->sendError('something went wrong', $th->getMessage(), 500);
        }
       

    }

    public function login(UserLoginRequest $request)
    {

        try{
            $user = User::where('email', $request->email)->first();

            if (is_null($user)) {
                return response()->json(['error' => true, 'message' => 'Invalid credentials'], 401);
            }
    
            if (Hash::check($request->password_or_pin, $user->password) || $request->password_or_pin === $user->pin) {
                $data['user'] = $user;
                $data['token'] = $user->createToken('Nova')->accessToken;

                return $this->sendResponse($data, 'Login Successful');
                // return response()->json(['is_correct' => true, 'message' => 'Login Successful', 'data' => $data], 200);

            } else {
                return $this->sendError('Invalid Credentials', null, 401);
               
            }
        } catch(\Exception $exception) {
            return $this->sendError('something went wrong', [$exception->getMessage()], 500);
        }

    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ], 204);
    }
}
