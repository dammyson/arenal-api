<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
