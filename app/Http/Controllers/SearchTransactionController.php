<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Wallet\FilterTransactionRequest;
use App\Models\TransactionHistory;
use App\Http\Requests\Wallet\SearchTransactionHistoryRequest;

class SearchTransactionController extends Controller
{
    
    public function searchTransactionHistory(SearchTransactionHistoryRequest $request, $wallet_id) {

        try {

        
            $data = TransactionHistory::whereHas('transaction', function($query) use($wallet_id, $request){
                $query->where('wallet_id', $wallet_id)
                    ->whereAny([
                        'receipient_name',
                        'transaction_id'
                    ], 'LIKE', '%'. $request['transaction-param']. '%');
            })->with('transaction')->get();

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

    public function filterTransactionHistory(FilterTransactionRequest $request, $wallet_id) {
        try {
            $perPage = 10;

            $transactionHistory = TransactionHistory::whereHas('transaction', function($query) use($request, $wallet_id) {
                $query->where('wallet_id', $wallet_id);
                
                if ($request->input('credit')) {
                    $query->where('is_credit', true);
                }

                if ($request->input('debit')) {
                    $query->where('is_credit', false);
                }

                if ($request->input('from_date')) {
                    $fromDate = $request->input('from_date');
                    $toDate = $request->input('to_date') ?? now();
                    $query->whereBetween('created_at', [$fromDate, $toDate]);
                }
            })->with('transaction')->paginate($perPage);


            return response()->json([
                'error' => false,
                'message' => 'result returned',
                'data' => $transactionHistory
            ], 200);
        
        } catch (\Throwable $th) 
        {
            report($th);
            return response()->json([
                "error" => true,
                "message" => "unable to search ",
                "error-details" => $th->getMessage()
            ], 500);

        }
        

    }
}
