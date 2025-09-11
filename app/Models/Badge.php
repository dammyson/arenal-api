<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [ "user_id", "name", "image_url", "brand_id", "points", "is_arena"];

    public function brand() {
        return $this->belongsTo(Brand::class);
    }
    
    public function audience() {
        return $this->belongsTo(Audience::class);
    }


}