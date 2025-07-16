<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name', 
        'image_url', 
        'industry_code', 
        'sub_industry_code', 
        'slug',
        'created_by',
        'client_id',
        'description'
    ];

    public function details() {
        return $this->hasMany(BrandDetail::class);
    }
}
