<?php

namespace App\Http\Controllers\wallet;

use Carbon\Carbon;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;

class WalletController extends Controller
{
    //
    public function histories(Request $request, $wallet_id)
    {
        $validated = $request->validate([
            'range' => 'nullable|integer'
        ]);

        try {
            $data = Transaction::where('wallet_id', $wallet_id)
                                    ->when(!is_null($validated['range']), function ($query) use ($validated) {
                                        $query->whereDate('created_at', '>=', Carbon::today()->subDays($validated['range']));
                                    })->get();
        } catch (\Throwable $th) {
            report($th);
            return response()->json(["message" => "unable to fetch transaction histories"], 500);
        }
        return TransactionResource::collection($data);
    }

    public function getWalletBalance(Request $request, $wallet_id)
    {
        try {
            $data = Wallet::find($wallet_id)->select('balance');

            
        } catch (\Throwable $th) {
            report($th);
            return response()->json(["message" => "unable to fetch transaction histories"], 500);
        }

        return response()->json([
            'error' => false,
            "message" => "wallet balance",
            $data
        ]);
        
    }
}
