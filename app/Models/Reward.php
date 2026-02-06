<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Reward extends Model
{
    use HasUuids, HasFactory;
    
    protected $fillable = [
        'campaign_id',
        'name',
        'type',
        'points_required',
        'stock_total',
        'stock_remaining',
        'is_active',
    ];

    public function rewardable(): MorphTo
    {
        return $this->morphTo();
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function isOutOfStock(): bool
    {
        return $this->stock_remaining !== null && $this->stock_remaining <= 0;
    }

}
