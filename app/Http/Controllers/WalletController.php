<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Brand;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\AudienceWallet;
use App\Models\BrandTransaction;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\Wallet\FundWalletRequest;
use App\Services\Payment\VerifyCardPaymentService;
use App\Services\Payment\VerifyBankTransferService;

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


    public function cardPayment(Request $request, $brandId, $ref_number) {        

        try {

            $response = (new VerifyCardPaymentService($request, $brandId, $ref_number))->run();

            return  $this->sendResponse($response, "wallet fund successfuly");
          
           

        }  catch (\Throwable $th) {
            report($th);

            return $this->sendError("unable to fund wallet", $th->getMessage());
           
        
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

    public function generateRandomRef() {
        return Str::random(16);
    }

    public function seerbit(Request $request) {
        try {
           
            $amount = $request->amount;
            $currency = $request->currency;
            $country = $request->country;
            
            $user = $request->user();

            $seerBitURL =env('SEER_BIT_URL');
            $seerBitURL = 'https://seerbitapi.com/api/v2/payments/initiates';
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
                    "paymentReference" => $this->generateRandomRef(),
                    "callbackUrl" => "http://checkout-seerbit.surge.sh",
                    "redirectUrl" => "http://checkout-seerbit.surge.sh",
                    "paymentType" => "TRANSFER"
                ]);

            return $this->sendResponse($response->json(), "account created successfully", 201);
            
        } catch (\Exception $e){

            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }   
    }


    public function verifyTransfer(Request $request, Brand $brand, $tranxReference) {
        try {

            $data = (new VerifyBankTransferService($request, $brand->id, $tranxReference))->run();
           
            return $this->sendResponse($data, "user wallet funded successfully");
              
            } catch (\Exception $e){

            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }  

            


            
        
    }
}
