<?php

namespace App\Services\Trivia;

use App\Models\User;
use App\Models\Trivia;
use Illuminate\Support\Str;
use App\Models\TriviaQuestion;
use Illuminate\Support\Facades\DB;
use App\Models\TriviaQuestionChoice;
use App\Services\BaseServiceInterface;

class IndexTrivaService implements BaseServiceInterface
{

    public function __construct()
    {
        
    }

    public function run()
    {
        return Trivia::whereHas('questions')
            ->with(['questions.choices'])    // Eager loads questions and their choices
            ->get();
    }
    
}
