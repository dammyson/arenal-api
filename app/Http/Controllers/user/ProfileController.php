<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileEditRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function profile(Request $request) 
    {
        try {
            $user = $request->user();
        
        } catch (\Throwable $throwable) {
            report($throwable);
            return response()->json([
                'error' => true,
                'message' => $throwable->getMessage()
            ], 500);
        }

        return response()->json([
            'error' => false,
            'message' => 'user data',
            $user
        ], 200);
    }

    public function profileEdit(ProfileEditRequest $request) 
    {
        try {
            $user = $request->user();

            $user->first_name = $request['first_name'] ?? $user->first_name;
            $user->last_name = $request['last_name'] ?? $user->last_name;
            $user->email = $request['email'] ?? $user->email;
            $user->phone_number = $request['phone_number'] ?? $user->phone_number;
            $user->profile_image = $request['profile_image'] ?? $user->profile_image;
            
            $user->save();

        } catch (\Throwable $throwable) {
            report($throwable);
            return response()->json([
                'error' => true,
                'message' => $throwable->getMessage()
            ], 500);
        }

        return response()->json([
            'error' => false,
            'message' => 'user data updated successfully',
            $user
        ], 200);
    }
    
    public function userInfo(Request $request)
    {
        try {

            $user = $request->user();
            
            $userInfo = [];
    
          
            $userTotalPoint = DB::table('campaign_leaderboards')
                ->where('audience_id', $user->id)
                ->select('total_points')
                ->sum('total_points');
            
        
    
            array_push($userInfo, $user->first_name, $user->profile_image, $userTotalPoint);
        
        }  catch (\Throwable $throwable) {
            report($throwable);
            return response()->json([
                'error' => true,
                'message' => $throwable->getMessage()
            ], 500);
        }

        return response()->json([
            'error' => false,
            'message' => 'user points',
            "user_info" => $userInfo
        ], 200);
    }
}
