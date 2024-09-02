<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\Http;
use App\Services\VerifyExternalTransaction;
use App\Services\GetTransactionCategoryID;
use App\Services\GetTransactionChannelID;
use App\Http\Resources\TransactionResource;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class TransactionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    
    public function __construct()
    {

    }
    
    /**
     * List all transactions for a given wallet ID
     *
     * @param  mixed $wallet_id
     * @return Response
     */
    public function index($wallet_id)
    {
        try {
            $transactions = Transaction::where('wallet_id', $wallet_id)->get();
        } catch (\Throwable $th) {
            report($th);
            return response()->json("resource not found", 400);
        }
        return TransactionResource::collection($transactions);
    }
    
    /**
     * Retrieve the transaction for the given ID.
     *
     * @param  mixed $id
     * @return Response
     */
    public function show($txId)
    {
        try {
            $transaction = Transaction::findOrFail($txId);
        } catch (\Throwable $th) {
            report($th);
            return response()->json("resource not found", 400);
        }
        
        return new TransactionResource($transaction);
    }

    // for test purpose
    public function storeTransaction(Request $request) {
        $tx = Transaction::create([
            'receipient_name' => $request['receipient_name'],
            'is_credit' => $request['is_credit'],
            'amount' => $request['amount'],
            'wallet_id' => $request['wallet_id']
        ]);
    
        return response()->json([
            'tx' => $tx
        ]);
    }

}
