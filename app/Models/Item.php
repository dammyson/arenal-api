<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasUuids, HasFactory;
    protected $fillable = [ 'name', 'description', 'sku' ];

    public function reward()
    {
        return $this->morphOne(Reward::class, 'rewardable');
    }
}
