<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class TriviaQuestion extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'trivia_questions';

    protected $keyType = 'string'; // UUID primary key
    public $incrementing = false; // UUIDs are not auto-incrementing

    protected $fillable = [
        'id',
        'question',
        'is_general',
        'points',
        'duration',
        'media_type',
        'asset_url',
        'difficulty_level',
        'company_id',
        'user_id',
    ];

    protected $casts = [
        'is_general' => 'boolean',
        'points' => 'decimal:2',
        'duration' => 'decimal:2',
    ];

    public function choices()
    {
        return $this->hasMany(TriviaQuestionChoice::class, 'question_id');
    }
}
