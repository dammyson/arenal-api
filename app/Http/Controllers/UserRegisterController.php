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

class UserRegisterController extends BaseController
{
    public function userRegister(RegisterUserRequest $request)
    {
    
        try {
            $user = (new CreateUserService($request))->run();
            // $companyData = (new CreateCompanyService($request, $user->id))->run();            
        
           
        } catch (\Exception $exception) {
            return $this->sendError(
                "something went wrong",
                ['error' => $exception->getMessage()],
                500
            );
        }
    
        // $data['user'] =  $user;
        // $data['company'] = $companyData['company'];
        // $data['company_user'] = $companyData['companyUser'];
        $data['token'] =  $user->createToken('Nova')->accessToken;
    
        return $this->sendResponse($data, 'User registration successful', 201);
     
    }

    public function newRegister(Request $request) {
        try {

            $user = User::where('email', $request['email_or_phone_no'])
                ->orWhere('phone_number', $request['email_or_phone_no'])  
                ->first();

            if($user) {
                return $this->sendResponse($user, true, 200);

            } else {

                // User::create([
                //     'email' => $request['email']
                // ]);
                $otp = $this->generateFourDigitOtp();

                $otp = Otp::create([
                    'email_or_phone_no' => $request->email_or_phone_no,
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

    public function verifyOtp(Request $request) {
        
        try {
            $otp = Otp::where('email_or_phone_no', $request['email_or_phone_no'])->where('otp', $request->otp)->first();
            
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

    public function newLogin(Request $request) {
        $user = User::where('email', $request['email_or_phone_no'])
            ->orWhere('phone_number', $request['email_or_phone_no'])
            ->first();

        if (Hash::check($request->password, $user->password)) {
                $data['user'] = $user;
                $data['token'] = $user->createToken('Nova')->accessToken;

                return $this->sendResponse($data, 'Login Successful');
                // return response()->json(['is_correct' => true, 'message' => 'Login Successful', 'data' => $data], 200);

        } else {
            return $this->sendError('Invalid Credentials', null, 401);
            
        }
    }


    public function googleRedirect(){
        return Socialite::driver('google')->redirect();
    }

    public function gooogleCallback() {

        // dd("I ran");
        try {
            $user = Socialite::driver('google')->stateless()->user();
          
        } catch (Throwable $e) {
            return response()->json([
                "error" => true,
                "message" => $e->getMessage()
            ], 500);
            // return redirect('/')->with('error', 'Google authentication failed.');
        }

        $existingUser = User::where('email', $user->email)->first();

        if ($existingUser) {
            $data['user'] =  $existingUser;
            $data['token'] =  $existingUser->createToken('Nova')->accessToken;
      
        } else {
            [$firstName, $lastName] = explode(" ", $user->name);
            $newUser = User::updateOrCreate([
                'email' => $user->email
            ], [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'password' => bcrypt(Str::random(16)), // Set a random password
                'email_verified_at' => now()
            ]);


            $data['user'] =  $newUser;
            $data['token'] =  $newUser->createToken('Nova')->accessToken;
        }

        return response()->json([
            "error" => false,
            "data" => $data
        ], 200);


    }
}
