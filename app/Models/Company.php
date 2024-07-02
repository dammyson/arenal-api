<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory, HasUuids;
    
    protected $fillable = [
        'company_name',
        'company_address',
        'company_logo',
        'company_rc',
        'company_email',
        'company_phone_number',
        'company_website',
        'company_city',
        'company_state',
        'company_country',
    ];
}
