<?php

namespace App\Http\Controllers;

use App\Models\Prize;
use App\Models\Trivia;
use App\Models\Campaign;
use App\Models\TriviaQuestion;
use App\Models\CampaignGamePlay;
use Illuminate\Support\Facades\DB;
use App\Models\TriviaQuestionChoice;
use Illuminate\Support\Facades\Auth;
use App\Services\Trivia\CreateService;
use App\Services\Trivia\IndexTrivaService;
use App\Services\Trivia\ShowTriviaService;
use App\Http\Requests\Trivia\StoreTriviaAnswers;
use App\Http\Requests\Trivia\StoreTriviaRequest;
use App\Services\Trivia\StoreTriviaAnswerService;
use App\Http\Requests\Trivia\StoreTriviaAnswerRequest;
use App\Http\Requests\TriviaQuestion\StoreTriviaQuestionsRequest;

class TriviaQuestionController extends BaseController
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
    public function show(Trivia $trivia){
        try {
            $data = (new ShowTriviaService($trivia->id))->run();
            return ["question_count" => count($data["questions"]), "data" => $data];
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'An error occurred while processing your request.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function processAnswers(Trivia $trivia, StoreTriviaAnswerRequest $request) {
        try {
            $data = (new StoreTriviaAnswerService($request, $request->validated()["questions"], $trivia))->run();
        
            return $this->sendResponse($data, "answer returned successfully");
        } catch (\Throwable $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }

      
    }

    // public function processAnswer(Trivia $trivia, StoreTriviaAnswers $request) {
    //     $points = 0;

    //     $triviaQuestionChoice = TriviaQuestionChoice::where("question_id", $request["question_id"])
    //             ->where('answer_id', $request->answer_id)->first();

    //     if ($triviaQuestionChoice->is_correct_choice) {
    //            $triviaQuestion = TriviaQuestion::find( $request["question_id"]);
    //            $points += $triviaQuestion->points;
    //     }

    //     return $points;

    // }
}