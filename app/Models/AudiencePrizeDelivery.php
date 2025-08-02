<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AudiencePrizeDelivery extends Model
{

    use HasUuids;

    protected $fillable = [
        'full_name', 
        'phone_number', 
        'email', 
        'delivery_address',
        'brand_audience_reward_id',
        'status'

    ];

    public function brandAudienceReward() {
        return  $this->belongsTo(BrandAudienceReward::class);
    }
}
