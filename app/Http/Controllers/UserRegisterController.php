<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\User;
use App\Models\Company;
use App\Models\CompanyUser;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Requests\Auth\RegisterAudienceRequest;
use App\Services\Company\CreateCompanyService;
use App\Services\Users\CreateUserService;

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
