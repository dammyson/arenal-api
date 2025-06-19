<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class SpinTheWheelSector extends Model
{
    use HasUuids;
    protected $fillable = [
        "spin_the_wheel_id", 
        "text", 
        "color", 
        "value", 
        "image_url", 
        "user_id", 
       
    ];

          
    public function spinTheWheel(){
        return $this->belongsTo(SpinTheWheel::class, 'spin_the_wheel_id', 'id');
    }

    /*

   
   
    

    

   
  

    */
}
