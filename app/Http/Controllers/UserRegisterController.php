<?php

namespace App\Http\Controllers;

use Throwable;
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
            $companyData = (new CreateCompanyService($request, $user->id))->run();            
        
           
        } catch (\Exception $exception) {
            return $this->sendError(
                "something went wrong",
                ['error' => $exception->getMessage()],
                500
            );
        }
    
        $data['user'] =  $user;
        $data['company'] = $companyData['company'];
        $data['company_user'] = $companyData['companyUser'];
        $data['token'] =  $user->createToken('Nova')->accessToken;
    
        return $this->sendResponse($data, 'User registration successful', 201);
     
    }

    public function newRegister(Request $request) {
        $user = User::where('email', $request['email'])->first();

        if($user) {
            return $this->sendResponse($user, true, 200);
        } else {

            $user = $this->generateFourDigitOtp();
            return $this->sendResponse($user, false, 404);
        }

    }

    public function generateFourDigitOtp() {
        $otp = rand(0000, 9999);
        return $otp;
    }

    public function newLogin(Request $request) {
        $user = User::where('email', $request['email'])->first();

        if (Hash::check($request->password_or_pin, $user->password) || $request->password_or_pin === $user->pin) {
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
