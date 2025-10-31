<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class SpinTheWheelButton extends Model
{
    use HasUuids;

    protected $fillable=[
        "spin_the_wheel_id",
        "color",
        "is_solid", 
        "border_radius", 
        "button_3d_styles", 
        "text", 
        "custom_button_url"
    ];

    public function spin_the_wheel_sector() {
        return $this->belongsTo(SpinTheWheelSector::class, 'spin_the_wheel_sector', 'id');
    } 
}
