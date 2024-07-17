<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;

class ChangePasswordController extends Controller
{
    public function changePassword(ChangePasswordRequest $request) 
    {
        

        try {
            $user =  $request->user();
            $user->password = $request['password'];
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