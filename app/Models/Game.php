<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['name', 'type', 'image_url', 'is_favorite', 'price', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rules()
    {
        return $this->hasMany(CampaignGameRule::class);
    }

    public function campaigns()
{
    return $this->belongsToMany(Campaign::class, 'campaign_games')
        ->using(CampaignGame::class)
        ->withPivot('details')
        ->withTimestamps();
}
}
