<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class SpinTheWheelSegment extends Model
{
    use HasUuids;

    protected $casts = [
        'probability' => 'decimal:2', // 2 is the number of decimal places
    ];

    protected $fillable = [
        "spin_the_wheel_id", 
        "label_text", 
        "label_color", 
        "background_color", 
        "icon", 
        "probability"
    ];

    public function spin_the_wheel_sector() {
        return $this->belongsTo(SpinTheWheel::class, 'spin_the_wheel_id', 'id');
    }
}
