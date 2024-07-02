<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class TransactionHistory extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'wallet_id',
        'receipient_name',
        'transaction_id',
        'amount'
    ];

    public function wallet() {
        return $this->belongsTo(Wallet::class);
    }
}
