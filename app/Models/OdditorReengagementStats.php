<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class OdditorReengagementStats extends Model
{
    use HasUuids;

    protected $fillable = ['email', 'abandoned_then_returned'];
}
