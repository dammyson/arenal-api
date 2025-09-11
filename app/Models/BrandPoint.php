<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class BrandPoint extends Model
{
    use HasUuids;
    protected $fillable = ['audience_id', 'brand_id', 'points', 'is_arena'];

    public function audience() {
        return $this->belongsTo(Audience::class);
    }

    public function brand() {
        return $this->belongsTo(Brand::class);
    }
}


