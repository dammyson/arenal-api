<?php

namespace App\Http\Controllers;

use App\Models\TransactionHistory;
use Illuminate\Http\Request;

class TransactionHistoryController extends Controller
{
    //

    public function storeTxHistory(Request $request, $wallet_id)
    {   
        $validated = $request->validate([
            'receipient_name' => 'required|string',
            'transaction_id' => 'required|string', //later this will be modified to be validated on the transaction table
            'amount' => 'required|integer'
        ]);
       
        try{
            $transactionHistory= TransactionHistory::create([
                'wallet_id' => $wallet_id,
                'receipient_name' => $validated['receipient_name'],
                'transaction_id' => $validated['transaction_id'],
                'amount' => $validated['amount']

            ]);
        } catch (\Throwable $th) {
            report($th);
            return response()->json([
                'error' => true,
                'message' => $th->getMessage()
            ], 500);
        } 
        
        return response()->json([
            'error' => false,
            'message' => "transaction added to history successfully",
            $transactionHistory
        ], 201);
    }

    public function getTxHistory(Request $request, $wallet_id)
    {
        try {
            $txHistory = TransactionHistory::where('wallet_id', $wallet_id)->get();
            // TransactionHistory::wallet(function($query) use($request){
            //     $query->user(function($query) use ($request) {
            //         $query->where('user_id', $request->user()->id);
            //     });
            // })->get();

        } catch (\Throwable $th)
        {
            report($th);
            return response()->json([
                'error' => true,
                'message' => $th->getMessage()
            ], 500);  
        }

        return response()->json([
            'error' => false,
            'message' => "transaction added to history successfully",
            $txHistory
        ], 200);

    }
}
