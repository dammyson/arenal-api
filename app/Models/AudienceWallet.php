<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AudienceWallet extends Model
{
    use HasUuids;

    protected $fillable = [
        'audience_id',
        'balance',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
    ];


    public function audience()
    {
        return $this->belongsTo(Audience::class);
    }
}
