<?php

namespace App\Models;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransactionHistory extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'transaction_id'
    ];

    public function wallet() {
        return $this->belongsTo(Wallet::class);
    }

    public function transaction() {
        return $this->belongsTo(Transaction::class);
    }
}
