<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory, HasUuids;
    
    protected $fillable = [
        'name',
        'address',
        'logo',
        'company_rc',
        'email',
        'phone_number',
        'website',
        'city',
        'state',
        'country',
    ];
}
