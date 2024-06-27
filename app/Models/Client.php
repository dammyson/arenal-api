<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory, HasUuids;
    
    protected $fillable = [
        'name',
        'image_url',
        'created_by',
        'company_id',
        'street_address',
        'city',
        'state',
        'nationality'
    ];
}
