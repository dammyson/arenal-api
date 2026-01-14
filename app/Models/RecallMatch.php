<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class RecallMatch extends Model
{
    use HasUuids;
    
    protected $fillable = ['name', 'image_url', 'game_id', 'user_id', 'entry_fee', 'campaign_id'];

  
    public function campaign() {
        return $this->belongsTo(Campaign::class);
    }
}
