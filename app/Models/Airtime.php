<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Airtime extends Model
{
    use HasUuids, HasFactory;
    protected $fillable = [ 'airtime_value', 'network' ];

    public function reward()
    {
        return $this->morphOne(Reward::class, 'rewardable');
    }
}
