<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name', 
        'image_url', 
        'industry_code', 
        'sub_industry_code', 
        'slug',
        'created_by',
        'client_id',
        'description',
        'is_arena',
        'closes_on',
        'daily_bonus',
        'high_score_bonus',
        'primary_color',
        'secondary_color'
    ];

    public function details() {
        return $this->hasMany(BrandDetail::class);
    }

    public function branches() {
        return $this->hasMany(Branch::class);
    }

    public function audienceBranch()
    {
        return $this->hasOne(AudienceBranch::class)
            ->where('audience_id', auth()->id());
    }
}