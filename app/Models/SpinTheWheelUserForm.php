<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class SpinTheWheelUserForm extends Model
{

    use HasUuids;
    protected $fillable=[
        "spin_the_wheel_id",
        "user_name",
        "user_email", 
        "phone_number",
        "is_marked_required",
        "is_user_name",
        "is_user_email",
        "is_phone_number",
        "show_from_before_spin"

    ];

    public function spinTheWheel() {
        return $this->belongsTo(SpinTheWheel::class, 'spin_the_wheel_id', 'id');
    }
}
