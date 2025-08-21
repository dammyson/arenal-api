<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class AudienceLiveStreak extends Model
{
    use HasUuids;
    protected $fillable = [
        'audience_id',
        'live_id',
        'streak_count',
        'last_joined'
    ];

    public function live() {
        return $this->belongsTo(Live::class);
    }

    public function audience() {
        return $this->belongsTo(Audience::class);
    }
}
