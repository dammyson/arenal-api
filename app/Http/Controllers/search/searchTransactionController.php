<?php

namespace App\Http\Controllers\search;

use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class searchTransactionController extends Controller
{
    public function searchTransaction(Request $request) {
        $validated = $request->validate([
            'transaction-param' => 'sometimes|required'
        ]);

        try {

           $data = Wallet::where('user_id', $request->user()->id)
                ->whereAny([
                    'recepient_name',
                    'transaction_id'
                ], $validated['transaction-param'] . '%');
        
        } catch (\Throwable $th) 
        {
            report($th);
            return response()->json(["message" => "unable to search "], 500);

        }

        return response()->json([
            'error' => false,
            'message' => 'result returned',
            'data' => $data
        ], 200);

    }
}
