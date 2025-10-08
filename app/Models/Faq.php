<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;



            // $table->string('');
            // $table->string('')
class Faq extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['question', 'answer', 'audience_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
