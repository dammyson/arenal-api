<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function logout(Request $request) 
    {
        try {
            
            $request->user()->token()->revoke();

        } catch (\Throwable $th) {
            report($th);
            return  response()->json([
                'error' => true,
                'message' => $th->getMessage()
            ]);
        }

        return  response()->json([
            'error' => false,
            'message' => "logout out successfully"
        ], 204);
    }
}
