<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class SpinTheWheelCustomGameText extends Model
{
    use HasUuids;
    protected $fillable = ["spin_the_wheel_id", "game_title", "description", "error_message", "style"];

    public function spin_the_wheel_sector() {
        return $this->belongsTo(SpinTheWheelSector::class, 'spin_the_wheel_id', 'id');
    }

}
