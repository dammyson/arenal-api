<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class LiveDaysName extends Model
{
    use HasUuids;
    protected $fillable = ['live_id', 'day_of_week', 'day_value'];

    public function live()
    {
        return $this->belongsTo(Live::class);
    }
}
