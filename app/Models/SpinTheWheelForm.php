<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class SpinTheWheelForm extends Model
{
    use HasUuids;
    protected $fillable=[
        "spin_the_wheel_id",
        "title",
        "description", 
        "text_style"
    ];

    public function spinTheWheel() {
        return $this->belongsTo(SpinTheWheel::class, 'spin_the_wheel_id', 'id');
    }
}
