<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $fillable = [
        'email_or_phone_no', 
        'otp',
        'is_verified'
    ];
}
