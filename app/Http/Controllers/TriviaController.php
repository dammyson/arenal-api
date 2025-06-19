<?php

namespace App\Http\Controllers;

use App\Http\Requests\Trivia\StoreTriviaRequest;
use App\Models\Trivia;
use Illuminate\Http\Request;
use App\Models\TriviaQuestion;
use App\Models\TriviaQuestionChoice;
use Illuminate\Support\Facades\Auth;
use App\Services\Trivia\CreateService;
use App\Services\Trivia\IndexTrivaService;
use App\Services\Trivia\CreateTriviaService;
use App\Http\Requests\TriviaQuestion\StoreTriviaQuestionsRequest;

class TriviaController extends BaseController
{
    public function store(StoreTriviaRequest $request)
    {

        try {

            $data = (new CreateTriviaService($request))->run();
           
            // Success response
           return $this->sendResponse($data, "trivia created successfully", 201);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'An error occurred while processing your request.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function index(Request $request){
        try {
            return ($user = Auth::user());
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'An error occurred while processing your request.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
