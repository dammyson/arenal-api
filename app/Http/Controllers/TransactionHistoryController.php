<?php

namespace App\Http\Controllers;

use App\Http\Requests\Wallet\TransactionHistoryRequest;
use App\Models\TransactionHistory;
use Illuminate\Http\Request;

class TransactionHistoryController extends Controller
{

    public function storeTxHistory(TransactionHistoryRequest $request, $wallet_id)
    {   
       
        try{
            $transactionHistory = TransactionHistory::create([
                'transaction_id' => $request->input('transaction_id')
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
            $txHistory = TransactionHistory::with('transaction')
                ->orderBy('created_at', 'DESC')
                ->get();
           

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
            'message' => "All Previous transaction",
            'tx_history' => $txHistory
        ], 200);

    }
}
