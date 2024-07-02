<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientContact extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'client_id', 
        'first_name', 
        'last_name', 
        'email', 
        'phone_number', 
        'is_primary'
    ];
}
