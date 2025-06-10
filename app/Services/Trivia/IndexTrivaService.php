<?php

namespace App\Services\Trivia;

use App\Models\TriviaQuestion;
use App\Models\TriviaQuestionChoice;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\BaseServiceInterface;

class IndexTrivaService implements BaseServiceInterface
{

    public function __construct()
    {
        
    }

    public function run()
    {
       return TriviaQuestion::with('choices')->get();
    }
    
}
