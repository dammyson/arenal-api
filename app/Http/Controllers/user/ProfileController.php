<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
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

    public function profileEdit(Request $request) 
    {
        $validated = $request->validate([
            'first_name'=> 'sometimes|required|string',
            'last_name'=> 'sometimes|required|string',
            'email'=> 'sometimes|required|email',
            'phone_number' => 'sometimes|required|string',
            'profile_image' => 'sometimes|string'
        ]);

        try {
            $user = $request->user();

            $user->full_name = $validated['first_name'] ?? $user->first_name;
            $user->full_name = $validated['last_name'] ?? $user->last_name;
            $user->email = $validated['email'] ?? $user->email;
            $user->phone_number = $validated['phone_number'] ?? $user->phone_number;
            $user->profile_image = $validated['profile_image'] ?? $user->profile_image;
            
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
            
            // $totalPoint = DB::table('leaderboard')
            //     ->where('audience_id', $user->id)
            //     ->select('audience_id', DB::raw('SUM(total_points) AS total_points'))
            //     ->get();
    
            array_push($userInfo, $user->full_name, $user->profile_image, $userTotalPoint);
        
        }  catch (\Throwable $throwable) {
            report($throwable);
            return response()->json([
                'error' => true,
                'message' => $throwable->getMessage()
            ], 500);
        }

        return response()->json([
            'error' => false,
            'message' => 'user data updated successfully',
            $userInfo
        ], 200);
    }
}
