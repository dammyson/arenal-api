<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Button extends Model
{
    use HasUuids;
    protected $fillable=[
        "spin_the_wheel_id",
        "button_color",
        "button_solid_style", 
        "button_outline_style", 
        "button_3d_styles", 
        "button_custom_png", 
        "has_custom_png"
    ];

    public function spin_the_wheel_sector() {
        return $this->belongsTo(SpinTheWheelSector::class, 'spin_the_wheel_sector', 'id');
    }

}
