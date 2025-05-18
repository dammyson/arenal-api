<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\AudienceLoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\UserLoginRequest;

class AudienceLoginController extends Controller
{
    public function login(AudienceLoginRequest $request)
    {

        try{
            $user = User::where('email', $request['email_or_phone'])
                ->orWhere('phone_number', $request['email_or_phone'])  
                ->first();

            if (is_null($user)) {
                return response()->json(['error' => true, 'message' => 'Invalid credentials'], 401);
            }
    
            if (Hash::check($request->password_or_pin, $user->password) || $request->password_or_pin === $user->pin) {
                $data['user'] = $user;
                $data['token'] = $user->createToken('Nova')->accessToken;

                return response()->json(['is_correct' => true, 'message' => 'Login Successful', 'data' => $data], 200);

            } else {
                return response()->json(['error' => true, 'message' => 'Invalid credentials'], 401);
            }
        }catch(\Exception $exception){
            return response()->json(['message' => $exception->getMessage()], 500);
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
