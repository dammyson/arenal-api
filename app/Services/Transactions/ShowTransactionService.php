<?php
namespace App\Services\Transactions;

use App\Models\Transaction;
use App\Services\BaseServiceInterface;

class ShowTransactionService implements BaseServiceInterface {
    protected $transactionId;

    public function __construct($transactionId) {
        $this->transactionId = $transactionId;
    }

    public function run() {
        return Transaction::findOrFail($this->transactionId);
    }

}