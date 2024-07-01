<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\RegisterAudienceRequest;

class AudienceRegisterController extends Controller
{
    public function registerAudience(RegisterAudienceRequest $request) {
        try {
            $user = User::create([
                ...$request->validated(),
                'is_audience' => true
            ]);
           
            $userWallet = Wallet::create([
                'user_id' => $user->id,
                'revenue_share_group' => 'audience'
            ]);

            
    
           
        } catch (\Exception $exception) {
            return response()->json(['error' => true, 'message' => $exception->getMessage()], 500);
        }
    
        $data['user'] =  $user;
        $data['token'] =  $user->createToken('Nova')->accessToken;
    
        return response()->json([
            'error' => false, 
            'message' => 'Client registration successful. Verification code sent to your email.', 
            'data' => $data,
            'wallet' => $userWallet
        ], 201);
    }
    
}
