<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ChangePasswordController extends Controller
{
    public function changePassword(ChangePasswordRequest $request) 
    {
        
        try {
            $user =  $request->user();
            
            if (!Hash::check($request['old_password'], $user->password)) {
                // throw error that old password dont match
                throw ValidationException::withMessages([
                    'old_password' => ['Old password is incorrect.']
                ]);
            }

            // Update the user's password
            $user->password = Hash::make($request['password']);
            $user->save();

        } catch (\Exception $exception){
            
            return response()->json(['error' => true, 'message' => $exception->getMessage()], 500);
        }

        return response()->json([
            'error' => false,
            'message' => 'password change successfully'
        ], 200);

    }
}
