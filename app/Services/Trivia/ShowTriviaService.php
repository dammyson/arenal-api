<?php

namespace App\Services\Trivia;

use App\Models\User;
use App\Models\Trivia;
use Illuminate\Support\Str;
use App\Models\TriviaQuestion;
use Illuminate\Support\Facades\DB;
use App\Models\TriviaQuestionChoice;
use App\Services\BaseServiceInterface;

class ShowTriviaService implements BaseServiceInterface
{
    protected $triviaId;
    public function __construct($triviaId)
    {
       $this->triviaId = $triviaId; 
    }

    public function run()
    {
        return Trivia::where('id', $this->triviaId)
            ->whereHas('questions')
            ->with(['questions.choices'])    // Eager loads questions and their choices
            ->get();
    }
    
}
