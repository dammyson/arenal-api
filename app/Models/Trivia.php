<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Trivia extends Model
{
    use HasUuids;
    //
    protected $fillable = ["name", "game_id", "image_url", "user_id"];

    public function questions() {
        return $this->hasMany(TriviaQuestion::class);
    }
}
