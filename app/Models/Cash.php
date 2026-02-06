<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cash extends Model
{
    use HasUuids, HasFactory;
    
    protected $fillable = [ 'amount_per_redemption' ];

    public function reward()
    {
        return $this->morphOne(Reward::class, 'rewardable');
    }

}
