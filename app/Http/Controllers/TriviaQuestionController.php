<?php

namespace App\Http\Controllers;

use App\Models\TriviaQuestion;
use App\Models\TriviaQuestionChoice;
use Illuminate\Support\Facades\Auth;
use App\Services\Trivia\CreateService;
use App\Services\Trivia\IndexTrivaService;
use App\Http\Requests\TriviaQuestion\StoreTriviaQuestionsRequest;

class TriviaQuestionController extends Controller
{
    public function storeMultiple(StoreTriviaQuestionsRequest $request)
    {
        $questionsData = $request->validated();


        try {
           $user = Auth::user();

            $service = new CreateService( $questionsData,  $user);
            $result = $service->run();

            // If result is an array with 'error' key, treat as failure
            if (is_array($result) && isset($result['error'])) {
                return response()->json($result, 500);
            }

            // Success response
            return response()->json([
                'message' => 'Questions created successfully',
                'data' => $result
            ], 201);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'An error occurred while processing your request.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function index(){
        try {
            return (new IndexTrivaService())->run();
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'An error occurred while processing your request.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
