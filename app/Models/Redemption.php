<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Redemption extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'user_id',
        'reward_id',
        'points_spent',
        'status',
        'metadata',
    ];

    public function reward()
    {
        return $this->belongsTo(Reward::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
