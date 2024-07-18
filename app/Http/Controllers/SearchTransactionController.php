<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TransactionHistory;
use App\Http\Requests\Wallet\SearchTransactionHistoryRequest;

class SearchTransactionController extends Controller
{
    public function searchTransaction(SearchTransactionHistoryRequest $request, $wallet_id) {

        try {

           $data = TransactionHistory::where('wallet_id', $wallet_id)
                ->whereAny([
                    'receipient_name',
                    'transaction_id'
                ], 'LIKE', '%'. $request['transaction-param']. '%')
                ->get();
        
        } catch (\Throwable $th) 
        {
            report($th);
            return response()->json([
                "error" => true,
                "message" => "unable to search ",
                "error-details" => $th->getMessage()
        ], 500);

        }

        return response()->json([
            'error' => false,
            'message' => 'result returned',
            'data' => $data
        ], 200);

    }
}
