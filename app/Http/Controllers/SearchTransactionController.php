<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Wallet\FilterTransactionRequest;
use App\Models\TransactionHistory;
use App\Http\Requests\Wallet\SearchTransactionHistoryRequest;
use Carbon\Carbon;

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
            $fromDate = $request->input('from_date');
            $status = $request->input('status');
            
            $transactionHistory = TransactionHistory::whereHas('transaction', function($query) use($request, $wallet_id, $status, $fromDate) {
                $query->where('wallet_id', $wallet_id);
                    
                
                if ($status == "credit") {
                    $query->where('is_credit', true);

                }

                if ($status == "debit") {
                    $query->where('is_credit', false);
                }
                
                if ($fromDate) {
                    $toDate = Carbon::parse($request->input('to_date') ?? now())->endOfDay();
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
