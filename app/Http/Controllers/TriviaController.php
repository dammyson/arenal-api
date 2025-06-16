<?php

namespace App\Http\Controllers;

use App\Models\TriviaQuestion;
use App\Models\TriviaQuestionChoice;
use Illuminate\Support\Facades\Auth;
use App\Services\Trivia\CreateService;
use App\Services\Trivia\IndexTrivaService;
use App\Http\Requests\TriviaQuestion\StoreTriviaQuestionsRequest;
use Illuminate\Http\Request;

class TriviaController extends Controller
{
    public function create(Request $request)
    {
        $questionsData = $request->validated()['questions'];

        try {
           $user = Auth::user();

           
            // Success response
            return response()->json([
                'message' => 'Questions created successfully',
                'data' => $user
            ], 201);

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
