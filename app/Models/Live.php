<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Live extends Model
{
    use HasUuids;
    
    protected $casts = [
        'is_live' => 'boolean',
    ];
    protected $fillable = ["user_id", "brand_id", "branch_id", "duration", 
        "checkIn_amount", "coins", "start_time", "end_time", "live_text"
    ];

    public function brand() {
        return   $this->belongsTo(Brand::class);
    }

    public function user() {
       return $this->belongsTo(User::class);
    }

    public function liveDays()
    {
        return $this->hasMany(LiveDaysName::class);
    }
}
