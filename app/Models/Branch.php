<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Branch extends Model
{
    
    use HasFactory, HasUuids;
    
    protected $fillable = [ "name", "brand_id"];

    public function brand() {
        return $this->hasMany(Brand::class);
    }

}
