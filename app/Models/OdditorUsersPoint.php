<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class OdditorUsersPoint extends Model
{
    use HasUuids;
    
    protected $fillable = [        
        'full_name',
        'email',
        'phone_no',
        'points',
        'brand_id',
        'campaign_id',
        'location',
        'status',
        'started_at',
        'ended_at'
    ];

    public function brand() {
        return $this->belongsTo(Brand::class);
    }

    public function campaign() {
        return $this->belongsTo(Campaign::class);
    }
}
