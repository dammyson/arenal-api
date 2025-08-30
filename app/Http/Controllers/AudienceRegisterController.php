<?php

namespace App\Http\Controllers;

use App\Models\Otp;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Audience;
use Illuminate\Http\Request;
use App\Models\AudienceWallet;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use App\Http\Requests\Auth\AudienceLoginRequest;
use App\Http\Requests\Auth\RegisterAudienceRequest;
use App\Http\Requests\Auth\ChangeAudiencePasswordRequest;
use App\Notifications\ArenaOTP;

class AudienceRegisterController extends BaseController
{
    public function registerAudience(RegisterAudienceRequest $request)
    {
        try {
            $data = $request->validated();

            $userData = [
                'is_audience' => true,
                'password' => bcrypt($data['password']),
            ];

            if (filter_var($data['email_or_phone'], FILTER_VALIDATE_EMAIL)) {
                $userData['email'] = $data['email_or_phone'];
            } else {
                $userData['phone_number'] = $data['email_or_phone'];
            }

            $audience = Audience::create($userData);

            $userWallet = AudienceWallet::create([
                'audience_id' => $audience->id,
                'revenue_share_group' => 'audience'
            ]);
        } catch (\Exception $exception) {
            return response()->json(['error' => true, 'message' => $exception->getMessage()], 500);
        }

        $data['user'] =  $audience;
        $data['token'] =  $audience->createToken('Nova')->accessToken;
        $data['password'] = null;

        return response()->json([
            'error' => false,
            'message' => 'Client registration successful. Verification code sent to your email.',
            'data' => $data,
            'wallet' => $userWallet
        ], 201);
    }



    public function checkAudience(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email_or_phone' => ['required', function ($attribute, $value, $fail) {
                    if (
                        !filter_var($value, FILTER_VALIDATE_EMAIL) &&
                        !preg_match('/^\+?[0-9]{10,15}$/', $value)
                    ) {
                        $fail('The ' . $attribute . ' must be a valid email address or phone number.');
                    }
                }],
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $user = Audience::where('email', $request['email_or_phone'])
                ->orWhere('phone_number', $request['email_or_phone'])
                ->first();

            if ($user) {
                return $this->sendResponse($user, true, 200);
            } else {

                $otpCode = $this->generateFourDigitOtp();

                $otp = Otp::create([
                    'email_or_phone_no' => $request->email_or_phone,
                    'otp' => $otpCode
                ]);

                  // Send notification to email or phone
                if (filter_var($request->email_or_phone, FILTER_VALIDATE_EMAIL)) {
                    Notification::route('mail', $request->email_or_phone)
                        ->notify(new ArenaOTP($otpCode));
                } else {
                    Notification::route('nexmo', $request->email_or_phone) // or 'vonage' depending on your SMS driver
                        ->notify(new ArenaOTP($otpCode));
                }
                // $user->notify(new ArenaOTP($otp));
                return $this->sendResponse($otp, false);
            }
        } catch (\Throwable $th) {

            return $this->sendError('something went wrong', $th->getMessage(), 500);
        }
    }

    public function generateFourDigitOtp()
    {
        $digits = 4;
        $otp = str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
        return $otp;
    }

    public function login(AudienceLoginRequest $request)
    {

        try {
            $user = Audience::where('email', $request['email_or_phone'])
                ->orWhere('phone_number', $request['email_or_phone'])
                ->first();

            if (is_null($user)) {
                return response()->json(['error' => true, 'message' => 'Invalid credentials'], 401);
            }

            if (Hash::check($request->password, $user->password) || $request->password === $user->pin) {
                $data['user'] = $user;
                $data['token'] = $user->createToken('Nova')->accessToken;

                return response()->json(['is_correct' => true, 'message' => 'Login Successful', 'data' => $data], 200);
            } else {
                return response()->json(['error' => true, 'message' => 'Invalid credentials'], 401);
            }
        } catch (\Exception $exception) {
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


    public function forgotPasswordPost(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email_or_phone' => ['required', function ($attribute, $value, $fail) {
                    if (
                        !filter_var($value, FILTER_VALIDATE_EMAIL) &&
                        !preg_match('/^\+?[0-9]{10,15}$/', $value)
                    ) {
                        $fail('The ' . $attribute . ' must be a valid email address or phone number.');
                    }
                }],
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $user = Audience::where('email', $request['email_or_phone'])
                ->orWhere('phone_number', $request['email_or_phone'])
                ->first();

            $otp = $this->generateFourDigitOtp();

            $otp = Otp::create([
                'email_or_phone_no' => $request['email_or_phone'],
                'otp' => $otp
            ]);

            return response()->json([
                "message" => "Otp to reset password has been sent to your email/phone number",
                'otp' => $otp
            ], 200);
            // return redirect()->to(route('login.get'))->with('success', "we have sent an email to reset password");

        } catch (\Throwable $throwable) {
            $message = $throwable->getMessage();
            return response()->json([
                'status' => 'failed',
                'message' => $message
            ], 500);
        }
    }


    public function changePassword(ChangeAudiencePasswordRequest $request)
    {
        try {
            $user = Audience::where('email', $request['email_or_phone'])
                ->orWhere('phone_number', $request['email_or_phone'])
                ->first();


            $user->password =  bcrypt($request['password']);
            $user->save();


            return response()->json([
                "message" => "Password changed successfully",

            ], 200);
            // return redirect()->to(route('login.get'))->with('success', "we have sent an email to reset password");

        } catch (\Throwable $throwable) {
            $message = $throwable->getMessage();
            return response()->json([
                'status' => 'failed',
                'message' => $message
            ], 500);
        }
    }
}
