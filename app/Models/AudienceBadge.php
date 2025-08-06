<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AudienceBadge extends Model
{
    use HasUuids;

    protected $fillable = [
        'audience_id',
        'brand_id',
        'badge_id'
    ];

    public function audience() {
        return $this->belongsTo(Audience::class);
    }

    public function badge() {
        return $this->belongsTo(Badge::class);
    }




}
