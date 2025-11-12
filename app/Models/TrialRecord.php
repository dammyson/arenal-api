<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class TrialRecord extends Model
{
    use HasUuids;

    protected $fillable = ['audience_id', 'spin_the_wheel_participation_details_id', 'trial_date', 'trial_count'];
    
    public function spinTheWheelParticipation() {
        return $this->belongsTo(SpinTheWheelParticipationDetails::class);
    }
}
