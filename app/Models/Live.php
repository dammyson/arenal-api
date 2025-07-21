<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Live extends Model
{
    use HasUuids;
    
    protected $fillable = ["user_id", "brand_id", "duration", "checkIn_amount", "coins"];

    public function brand(){
        $this->belongsTo(Brand::class);
    }

    public function user(){
        $this->belongsTo(User::class);
    }
}
