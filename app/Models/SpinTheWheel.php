<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class SpinTheWheel extends Model
{
    use HasUuids;
    protected $fillable = ["name", "game_id", "image_url", "user_id"];

    public function spinTheWheelSectors(){
        return $this->hasMany(SpinTheWheelSector::class, 'spin_the_wheel_id', 'id');
    }

    public function spinTheWheelForms() {
        return $this->hasMany(SpinTheWheelForm::class, 'spin_the_wheel_id', 'id');
    }
    public function spinTheWheelBackground() {
        return $this->hasMany(SpinTheWheelBackground::class, 'spin_the_wheel_id', 'id');
    }

    public function spinTheWheelSegments() {
        return $this->hasMany(SpinTheWheelSegment::class, 'spin_the_wheel_id', 'id');
    }

    public function spinTheWheelUserForms() {
        return $this->hasMany(SpinTheWheelUserForm::class, 'spin_the_wheel_id', 'id');
    }

    public function spinTheWheelRewardSetups() {
        return $this->hasMany(spinTheWheelRewardSetup::class, 'spin_the_wheel_id', 'id');
    }

    public function spinTheWheelCustomGameTexts() {
        return $this->hasMany(SpinTheWheelCustomGameText::class, 'spin_the_wheel_id', 'id');
    }
}
