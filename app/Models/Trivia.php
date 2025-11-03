<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Trivia extends Model
{
    use HasUuids;
    //
   protected $fillable = [
        "name", 
        "game_id", 
        "brand_id", 
        "campaign_id", 
        "image_url", 
        "user_id", 
        "entry_fee",
        "time_limit"
    ];


    public function questions() {
        return $this->hasMany(TriviaQuestion::class);
    }

    public function game() {
        return $this->belongsTo(Game::class);
    }



}
