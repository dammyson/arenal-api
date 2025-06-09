<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Audience extends Authenticatable
{
    use HasFactory, HasApiTokens, HasUuids;
    protected $fillable = ['first_name', 'last_name', 'email', 'phone_number', 'profile_image', 'password'];
    protected $hidden = ['password', 'remember_token'];

    public function leaderboards() {
      return  $this->hasMany(CampaignLeaderboard::class, 'audience_id');
    }

    public function wallet() {
      return $this->hasOne(Wallet::class);
    }
    
}
