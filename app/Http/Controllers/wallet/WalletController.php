<?php

namespace App\Http\Controllers\wallet;

use Carbon\Carbon;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Wallet\FundWalletRequest;

class WalletController extends Controller
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
            $userWallet
        ], 201);


    }


    public function fundWallet(FundWalletRequest $request, $wallet_id) {
        

        try {
            
            $wallet = Wallet::find($wallet_id);
            $wallet->balance += (int) $request['amount'];
            $wallet->save();

        }  catch (\Throwable $th) {
            report($th);
            return response()->json([ 
                "error" => true,
                "message" => "unable to fetch transaction histories"
            ], 500);
        
        }

        return response()->json([ 
            "error" => false,
            "message" => "wallet funded successfully",
            $wallet
        ], 500);
    }

   

    public function getWalletBalance(Request $request, $wallet_id)
    {
        try {
            $wallet = Wallet::find($wallet_id);
            $walletBalance = $wallet->balance;

            
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
            "walletBalance" => $walletBalance
        ]);
        
    }
}
