<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class OdditorEducatorPage extends Model
{
    //  

    use HasUuids;

    protected $fillable = [
        'title',
        'description',
        'audit_count',
        'overcharge_count',
        'cities_served',
        'button_header_text',
        'button_subheader_text',
        'brand_id'
    ];

    public function brand() {
        return $this->belongsTo(Brand::class);
    }
}
