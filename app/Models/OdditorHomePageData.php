<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class OdditorHomePageData extends Model
{
    use HasUuids;

    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'primary_color',
        'secondary_color',
        'btn_text',
        'brand_id'
    ];
}
