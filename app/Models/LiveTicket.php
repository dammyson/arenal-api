<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class LiveTicket extends Model
{
    use HasUuids;

    protected $fillable = ["audience_id", "live_id", "coined_earned", "is_live", 'ticket_id'];

    public function audience() {
        return $this->belongsTo(Audience::class);
    }

    public function live() {
        return $this->belongsTo(Live::class);
    }
}
