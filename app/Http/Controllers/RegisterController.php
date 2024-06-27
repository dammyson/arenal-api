<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\VerificationMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    //

    public function clientRegister(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone_number' => 'required|numeric|digits:11|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            "is_audience" => 'sometimes|boolean'
        ]);
    
        try {
            $create = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number ,
                'first_name' => $request->first_name ,
                'last_name' => $request->last_name ,
                "is_audience" => $request->is_audience
            ]);
    
            // Generate a random verification code
            // $verificationCode = $this->generateVerificationCode();
    
            // Send verification email
            // $this->sendVerificationCode($create->email, $verificationCode);
        } catch (\Exception $exception) {
            return response()->json(['error' => true, 'message' => $exception->getMessage()], 500);
        }
    
        $data['user'] =  $create;
        $data['token'] =  $create->createToken('Nova')->accessToken;
    
        return response()->json(['error' => false, 'message' => 'Client registration successful. Verification code sent to your email.', 'data' => $data], 201);
    }
    
    
    public function resetPIN(Request $request)
    {
         $request->validate([
            'phone_number' => 'required|numeric|digits:11',
        ]);

        try{
                $reset = User::where('phone_number', $request->phone_number)->first();
                if ($reset == null) {
                    return response()->json(['error' => true, 'message' => 'phone_number does not exist'], 404);
                }

                // $code = $this->generateVerificationCode();

                // $this->sendVerificationCode($validated['email'],$code);
                

            }catch (\Exception $exception){
            return response()->json(['error' => true, 'message' => $exception->getMessage()], 500);
        }
        return response()->json(['error' => false, 'message' => 'prompt user to enter new pin', 'data' => []], 200);
    }

    public function completePINReset(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|numeric|digits:11',
            'otp' => 'required|string',
            'new_pin' => 'required|string'
        ]);

        try{
            $user = User::where('phone_number', $request->phone_number)->first();

            if (!$user) {
                return response()->json(['error' => true, 'message' => 'User not found'], 404);
            }
        

            // verify otp then save password else return 
            if ($user && $request->otp === '1111') {
                $user->pin = $request->new_pin;
                $user->save();
                $code = 200;
            } else {
                $code = 400; 
             
            }            
        }catch (\Exception $exception){
            return response()->json(['error' => true, 'message' => $exception->getMessage()], 500);
        }
        return response()->json(['error' => false, 'message' =>'PIN Reset completed', 'data' => $user],  $code);
    }


    public function createPIN(Request $request)
    {
        $request->validate([
            'pin' => 'required|numeric|digits:4',
            'phone_number' => 'required|numeric|digits:11',
        ]);
    
        try {
            $user = User::where('phone_number', $request->phone_number)->first();
    
            if (!$user) {
                return response()->json(['status' => false, 'message' => 'User not found'], 404);
            }
    
            $user->pin = $request->pin;
            $user->save();
    
    
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => $exception->getMessage()], 500);
        }
    
        return response()->json(['status' => true, 'message' => 'Pin updated successfully'], 200);
    }
    
    public function sendVerificationCode($email, $code)
    {
        // return Mail::to($email)->send(new VerificationMail($code));
    }
    
    public function generateVerificationCode($length = 6)
    {
        return Str::random($length);
    }
    public function verify(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|numeric|digits:11',
            'otp' => 'required|string'
        ]);

        try{

            $user = User::where('phone_number', $request->phone_number)->first();

            if (!$user) {
                return response()->json(['status' => 400, 'message' => 'User not found'], 400);
            }
            
            // logger("Request OTP: {$request->otp}, User OTP: {$user->otp}");


            if ($user && trim($request->otp) === '1111') {
                // logger("OTP is correct for user: {$user->phone_number}");

                // Update the status if OTP is '1111'
                $user->update([
                    'status' => 1, 
                ]);

                $code = 200;
            } else {
                return response()->json(['status' => false, 'message' => 'Invalid OTP'], 400);
            }

        }catch (\Exception $e){
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
        return response()->json(['status' => true, 'message' => 'Phone Number Verified', 'data' => $user], $code);
    }
}
