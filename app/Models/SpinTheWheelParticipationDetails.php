<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class SpinTheWheelParticipationDetails extends Model
{
    use HasUuids;
    
    protected $fillable=[
        "spin_the_wheel_id",
        "is_free",
        "entry_fee"
    ];

    public function spinTheWheel() {
        return $this->belongsTo(SpinTheWheel::class, 'spin_the_wheel_id', 'id');
    }
}
