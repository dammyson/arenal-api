<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class SpinTheWheelBackground extends Model
{
    use HasUuids;

     protected $fillable = [
        "spin_the_wheel_id",
        "backgound_gradient", 
        "background_color", 
        "background_image", 
        "interactive_component"

    ];

          
    public function spinTheWheel(){
        return $this->belongsTo(SpinTheWheel::class, 'spin_the_wheels_id', 'id');
    }
}
