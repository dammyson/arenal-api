<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Wallet extends Model
{
    use HasFactory, HasUuids;
    
    protected $fillable = [
        'user_id',
        'balance',
        'revenue_share_group'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function transactionsHistory() {
        return $this->hasMany(TransactionHistory::class);
    }
}
