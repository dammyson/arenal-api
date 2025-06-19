<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class SpinTheWheelRewardSetup extends Model
{
    use HasUuids;
    protected $fillable=[
        "spin_the_wheel_id",
        "reward_name",
        "limit_setting", 
        "delivery_method",
        "custom_success_message",
        "custom_button",
    ];

    public function spinTheWheelRewardSetup() {
        return $this->belongsTo(SpinTheWheel::class, 'spin_the_wheel_id', 'id');
    }
}
