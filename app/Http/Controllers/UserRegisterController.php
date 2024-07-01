<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use App\Models\CompanyUser;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\RegisterAudienceRequest;
use App\Http\Requests\Auth\RegisterUserRequest;

class UserRegisterController extends Controller
{
    public function userRegister(RegisterUserRequest $request)
    {
    
        try {

            $user = User::create($request->validated());          
          
            $company = Company::create($request->validated());


            if ( isset($company->id) ) {
                // add the company user relation in the table
                $companyUser = CompanyUser::updateOrCreate([
                    'company_id' => $company->id,
                    
                ], ['user_id' =>  $user->id]);
            } else {
                return response()->json([
                    'error' => true,
                    'message' => "Company not found"

                ], 404);
            }
           
        } catch (\Exception $exception) {
            return response()->json(['error' => true, 'message' => $exception->getMessage()], 500);
        }
    
        $data['user'] =  $user;
        $data['token'] =  $user->createToken('Nova')->accessToken;
    
        return response()->json([
            'error' => false, 
            'message' => 'User registration successful', 
            'data' => $data, 
            'company' => $company,
            'company_user' => $companyUser
        ], 201);
    }
}
