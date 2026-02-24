<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorldCustomFirstPage extends Model
{
    protected $fillable = [
        'header_one',
        'header_two',
        'header_two_description',
        'header_three',
        'header_three_description',
        'btn_text',
        'brand_id'
    ];


    public function brand() {
        return $this->belongsTo(Brand::class);
    }
}
