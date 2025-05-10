<?php

namespace App\Services\Transactions;

use App\Models\Transaction;
use App\Services\BaseServiceInterface;
use Illuminate\Support\Facades\Request;

class StoreTransactionService implements BaseServiceInterface{
    protected $request;
    protected $walletId;

    public function __construct(Request $request, $walletId) {
        $this->request = $request;
        $this->walletId = $walletId;
    }

    public function run() {
        return Transaction::create([
            'receipient_name' => $this->request['receipient_name'],
            'is_credit' => $this->request['is_credit'],
            'status' => $this->request['status'],
            'amount' => $this->request['amount'],
            'wallet_id' => $this->walletId
        ]);
    }
}