<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AudienceDailyBonus extends Model
{
    
    use HasFactory, HasUuids;
    protected $fillable = ['audience_id', 'brand_id', 'bonus_date', 'is_arena'];
}
