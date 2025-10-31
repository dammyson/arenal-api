<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class SpinTheWheelAds extends Model
{
    use HasUuids;

    protected $fillable = [
        "spin_the_wheel_id",
        "image_url", 
        "video_url",

    ];
          
    public function spinTheWheel(){
        return $this->belongsTo(SpinTheWheel::class, 'spin_the_wheels_id', 'id');
    }
    
}
