<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArenaAudienceBadges extends Model
{
    use HasFactory, HasApiTokens, HasUuids;
    protected $fillable = ['arena_badge_id', 'audience_id'];

    public function audience() {
        return $this->belongsTo(Audience::class);
    }

    public function arenaBadge() {
        return $this->belongsTo(ArenaBadges::class);
    }

}
