<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class TriviaQuestionChoice extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'trivia_question_choices';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'question_id',
        'choice',
        'is_correct_choice',
        'media_type',
        'asset_url',
    ];

    protected $casts = [
        'is_correct_choice' => 'boolean',
    ];

  
    public function question()
    {
        return $this->belongsTo(TriviaQuestion::class, 'question_id');
    }
}
