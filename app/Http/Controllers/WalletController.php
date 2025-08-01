<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\AudienceWallet;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\Wallet\FundWalletRequest;

class WalletController extends BaseController
{
    public function createWallet(Request $request) {
        $user = $request->user();

        try {
            $userWallet = Wallet::create([
                'user_id' => $user->id,
                'revenue_share_group' => 'audience'
            ]);

        }  catch (\Throwable $th) {
            report($th);
            return response()->json([ 
                "error" => true,
                "message" => "unable to fetch transaction histories"
            ], 500);
        }

        return response()->json([
            "error" => false,
            'user_wallet' => $userWallet
        ], 201);


    }


    public function fundWallet(FundWalletRequest $request) {
        

        try {
            $user = $request->user();
            // dd($user);
            $wallet = $user->wallet;
    
            if (!$wallet) {
                // Optionally handle case where wallet doesn't exist
                return response()->json(['message' => 'Wallet not found.'], 404);
            }
    
            $wallet->balance += (int) $request->amount;
            $wallet->save();
    
            return response()->json(['message' => 'Wallet funded successfully.']);
            
            // $wallet = Wallet::find($wallet_id);
            // $wallet->balance += (int) $request['amount'];
            // $wallet->save();

        }  catch (\Throwable $th) {
            report($th);
            return response()->json([ 
                "error" => true,
                "message" => "unable to fund wallet",
                "actual_message" => $th->getMessage()
            ], 500);
        
        }
    }


    public function cardPayment(Request $request, $ref_number) {        

        try {
            $user = $request->user();

            $paymentChannel = $request->query('payment_channel');
            $paystackVerifyUrl = config('app.paystack.verify_url');
            $paystackBearerToken = config('app.paystack.bearer_token');

            $flutterVerifyUrl = config('app.flutterwave.verify_url');
            $flutterBearerToken = config('app.flutterwave.bearer_token');

            $wallet = $user->wallet;
    
            if (!$wallet) {
                return response()->json(['message' => 'Wallet not found.'], 404);
            }

            if ($paymentChannel == "paystack") {
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Authorization' => "Bearer " . $paystackBearerToken
                
                ])->get("{$paystackVerifyUrl}{$ref_number}");

                $responseData = $response->json();
                // dd($responseData);

                if (array_key_exists('data', $responseData) && array_key_exists('status', $responseData['data']) && ($responseData['data']['status'] === 'success')) {
                    $amount = $responseData["data"]["amount"];

                    $amount =  round($amount / 100, 2);                   
                    
                    $wallet->balance += $amount;
                
                    $wallet->save();

                    return $this->sendResponse($wallet, "user wallet funded successfully");
                    // dump($wallet->balance);
                }

                
                return $this->sendError("unable to fund wallet, pls try again later");



            } else if ($paymentChannel == "flutterwave") {

                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Authorization' => "Bearer " . $flutterBearerToken
                
                ])->get("{$flutterVerifyUrl}{$ref_number}");


                $responseData = $response->json();

                if (array_key_exists('data', $responseData) && array_key_exists('status', $responseData['data']) && ($responseData['data']['status'] === 'success')) {
                    $amount = $responseData["data"]["amount"];
                    $amount = round($amount, 2);

                    $wallet->balance += $amount;
                    $wallet->save();

                    return $this->sendResponse($wallet, "user wallet funded successfully");
                }

                return $this->sendError("unable to fund wallet, pls try again later");

            }
           

        }  catch (\Throwable $th) {
            report($th);
            return response()->json([ 
                "error" => true,
                "message" => "unable to fund wallet",
                "actual_message" => $th->getMessage()
            ], 500);
        
        }
    }


    public function deductFee(Request $request) {
        
        
        try {
            $user = $request->user();
            $gameFee = $request->input('game_fee');
            $wallet = $user->wallet;
    
            if (!$wallet) {
                // Optionally handle case where wallet doesn't exist
                return response()->json(['message' => 'Wallet not found.'], 404);
            }
            $walletBalance = (int) $wallet->balance;
            $gameFee = (int) $gameFee;
            // dd($walletBalance, $gameFee);

            if ($walletBalance < $gameFee) {
                return  response()->json(['message' => 'insufficient funds'], 422);
            }

            DB::beginTransaction();
                $wallet->balance -= (int) $gameFee;
                $wallet->save();
    
            DB::commit();

            return response()->json([
                'message' => 'successfull',
                'game_fee' => $gameFee,
                'user_balance' => $wallet->balance
            ]);
            
            // $wallet = Wallet::find($wallet_id);
            // $wallet->balance += (int) $request['amount'];
            // $wallet->save();

        }  catch (\Throwable $th) {
            report($th);
            return response()->json([ 
                "error" => true,
                "message" => "unable to deduct funds from wallet",
                "actual_message" => $th->getMessage()
            ], 500);
        
        }
    }

   

    public function getWalletBalance(Request $request)
    {
        try {
            $audienceId = $request->user()->id;
            $wallet = AudienceWallet::where('audience_id', $audienceId)->first();

            
        } catch (\Throwable $th) {
            report($th);
            return response()->json([
                "error" => true,
                "message" => "unable to fetch transaction histories"
            ], 500);
        }

        return response()->json([
            'error' => false,
            "message" => "wallet balance",
            "walletBalance" => $wallet
        ]);
        
    }


    public function getSeerBitToken() {
        $getTokenUrl = env('SEER_BIT_BEARER_TOKEN_URL');
        $seerBitSecretKey =env('SEER_BIT_SECRET_KEY');
        $seerBitPublicKey =env('SEER_BIT_PUBLIC_KEY');

        $response = Http::withHeaders([
                    'Content-Type' => 'application/json'
                ])->post($getTokenUrl,[
                    "key" => "{$seerBitSecretKey}.{$seerBitPublicKey}"
                ]);
        
        return $response->json();
    }

    public function seerbit(Request $request) {
        try {
           
            $amount = $request->amount;
            $currency = $request->currency;
            $country = $request->country;
            
            $user = $request->user();

            $seerBitURL =env('SEER_BIT_URL');
            $seerBitBearerKey = "bzSlv4IUp3GNQkNT0qqWh+ulbYbuTT/5JtDZHUAg+hxSVPUWuZLEbg/G29GOTlL/EAma3YCrDJfYhM+HFjy68GhRmDFDbab98Mzd55Pz4DW84em1uccL50F2fGm73l8m";
            $seerBitPublicKey =env('SEER_BIT_PUBLIC_KEY');
    
            $response = Http::withHeaders([
                    'Authorization' =>  'Bearer ' . $seerBitBearerKey, 
                    'Content-Type' => 'application/json'
                ])->post($seerBitURL, [
                    "publicKey" => $seerBitPublicKey,
                    "amount" => $amount,
                    "fullName" => $user->first_name ?? "test name",
                    "mobileNumber" => $user->phone_number ?? "08106031878",
                    "email" => $user->email ?? "test@gmail.com",
                    "currency" => $currency, //"NGN"
                    "country" => $country, //"NG"
                    "paymentReference" => "dsfafdafkasdjfssdddsddffsadfafjskasjfererer",
                    "callbackUrl" => "http://checkout-seerbit.surge.sh",
                    "redirectUrl" => "http://checkout-seerbit.surge.sh",
                    "paymentType" => "TRANSFER"
                ]);

            return $this->sendResponse($response->json(), "account created successfully", 201);
            
        } catch (\Exception $e){

            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }   
    }


    public function verifyTransfer(Request $request, $tranxReference) {
        try {

            $user = $request->user();


            $seerBitBearerKey = "bzSlv4IUp3GNQkNT0qqWh+ulbYbuTT/5JtDZHUAg+hxSVPUWuZLEbg/G29GOTlL/EAma3YCrDJfYhM+HFjy68GhRmDFDbab98Mzd55Pz4DW84em1uccL50F2fGm73l8m";

            $seerBitVerifyUrl = env('SEER_BIT_VERIFY_URL');

            $response = Http::withHeaders([
                    'Authorization' =>  'Bearer ' . $seerBitBearerKey, 
                    'Content-Type' => 'application/json'
                ])
                
                ->get("{$seerBitVerifyUrl}{$tranxReference}");

                //             {
                // "success": true,
                // "data": {
                //     "status": "SUCCESS",
                //     "data": {
                //         "code": "00",
                //         "message": "Successful",
                //         "payments": {
                //             "redirectLink": "http://checkout-seerbit.surge.sh",
                //             "amount": 200,
                //             "bankName": "TEST BANK",
                //             "mobilenumber": "08012335588",
                //             "publicKey": "SBTESTPUBK_VR9e7A2lzf0zplz4CvwZ15gmBKeg2XaW",
                //             "paymentType": "TRANSFER",
                //             "gatewayMessage": "Successful",
                //             "gatewayCode": "00",
                //             "gatewayref": "SEEROF0D1P9C2KI1V1S",
                //             "businessName": "Tech & Sons",
                //             "mode": "test",
                //             "callbackurl": "http://checkout-seerbit.surge.sh",
                //             "redirecturl": "http://checkout-seerbit.surge.sh",
                //             "channelType": "TRANSFER",
                //             "sourceIP": "102.88.53.129",
                //             "country": "NG",
                //             "currency": "NGN",
                //             "paymentReference": "dsfafdafkasdjfsssddffsfjskasjfererer",
                //             "network": "UNKNOWN",
                //             "reason": "Successful",
                //             "transactionProcessedTime": "2025-07-30 13:00:34.550101871"
                //         },
                //         "customers": {
                //             "customerId": "SBTff3895bbcab0b6c5311b",
                //             "customerName": "Emeka Genye",
                //             "customerMobile": "08012335588",
                //             "customerEmail": "emeka@e.com"
                //         }
                //     }
                // }
           $responseData = $response->json();
            
            // return $responseData;
            if ($responseData["status"] ===  "SUCCESS" && $responseData["data"]["code"] === "00") {

                    $paymentInfo = $responseData["data"]["payments"];
                    $data["paymentType"] = $paymentInfo["paymentType"];
                    $data["paymentReference"] = $paymentInfo["paymentReference"];
                    $data["amount"] = $paymentInfo["amount"];
                    $data["transactionProcessedTime"] = $paymentInfo["transactionProcessedTime"];
                    $data["customer_email"] =  $responseData["data"]["customers"]["customerEmail"];
                    $data["customer_phone_number"] =  $responseData["data"]["customers"]["customerMobile"];
                    

                    if ($data["customer_email"] == $user->email) {
                        return $this->sendError("account not funded ", ['error' => "account does not match the user"], 500);

                    }
                    return $data;
            }

            

            return $this->sendResponse( "account created successfully", 201);
            
        } catch (\Exception $e){

            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }   
    }
}
