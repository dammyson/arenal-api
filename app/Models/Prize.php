<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prize extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = ["name", "description", "campaign_id", "game_id", "brand_id"];
}
