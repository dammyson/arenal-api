<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class SpinTheWheelSetUserForm extends Model
{
    use HasUuids;

    protected $fillable=[
        "spin_the_wheel_id",
        "is_user_name",
        "is_user_email", 
        "is_phone_number",
        "is_marked_required",
        'title',
        'description',
        'text_style',
        'show_before_spin',
    ];


    protected $casts = [
        'is_user_name' => 'boolean',
        'is_user_email' => 'boolean',
        'is_phone_number' => 'boolean',
        'is_marked_required' => 'boolean',
        'show_before_spin' => 'boolean',
    ];

    public function spinTheWheel() {
        return $this->belongsTo(SpinTheWheel::class, 'spin_the_wheel_id', 'id');
    }
}
