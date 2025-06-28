<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AudienceSpinTheWheelReward extends Model
{
    use HasUuids;

    protected $fillable = ['spin_the_wheel_id', 'audience_id', 'prize'];

    public function spinTheWheel() {
        return  $this->belongsTo(SpinTheWheel::class);
    }

    public function audience() {
        return  $this->belongsTo(Audience::class);
    }
}
