<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArenaDemography extends Model
{
    //
    
    
    use HasFactory, HasApiTokens, HasUuids;

    protected $fillable = ['audience_id', 'favorite_team', 'favorite_sport', 'instagram_handle', 'favorite_music_genre', 'favorite_food'];

    public function audience() {
        return $this->belongsTo(Audience::class);
    }
}
