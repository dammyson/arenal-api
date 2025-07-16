<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandDetail extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ["brand_id", "detail", "user_id"];
     
    public function brand() {
        return $this->belongsTo(Brand::class);
    }
}
