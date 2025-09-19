<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BrandAudienceReward extends Model
{
    use HasFactory, HasUuids;
    //points
    // protected $fillable = ["brand_id", "detail", "points", "audience_id", "prize_id", "is_arena", "is_redeemed"];
    protected $fillable = ["brand_id", "audience_id", "prize_id", "is_arena", "is_redeemed"];

    public function prize() {
        return $this->belongsTo(Prize::class);
    }
}
