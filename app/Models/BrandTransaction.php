<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class BrandTransaction extends Model
{
    //
    use HasUuids;
    protected $fillable = [
        'audience_id', 
        'wallet_id',
        'brand_id',
        'payment_channel', 
        'payment_channel_description',
        'status',
        'is_credit',
        'sender_name',
        'amount'
    
    
    ];

    public function audience() {
        return $this->belongsTo(Audience::class);
    }
      
}
