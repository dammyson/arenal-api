<?php

namespace App\Services\Transactions;

use App\Models\Transaction;
use App\Services\BaseServiceInterface;
use Illuminate\Support\Facades\Request;

class IndexTransactionService implements BaseServiceInterface{
    protected $walletId;

    public function __construct($walletId) {
        $this->walletId = $walletId;
    }

    public function run() {
        return Transaction::where('wallet_id', $this->walletId)->get();

    }
}