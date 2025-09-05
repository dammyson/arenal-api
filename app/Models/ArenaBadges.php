<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArenaBadges extends Model
{
    use HasFactory, HasApiTokens, HasUuids;
    protected $fillable = ['name', 'points'];



    //
}
