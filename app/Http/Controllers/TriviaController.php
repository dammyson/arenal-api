<?php

namespace App\Http\Controllers;

use App\Http\Requests\Trivia\StoreTriviaRequest;
use App\Http\Requests\TriviaQuestion\StoreTriviaQuestionsCsvRequest;
use App\Models\Trivia;
use Illuminate\Http\Request;
use App\Models\TriviaQuestion;
use App\Models\TriviaQuestionChoice;
use Illuminate\Support\Facades\Auth;
use App\Services\Trivia\CreateService;
use App\Services\Trivia\IndexTrivaService;
use App\Services\Trivia\CreateTriviaService;
use App\Http\Requests\TriviaQuestion\StoreTriviaQuestionsRequest;
use App\Models\Brand;
use Illuminate\Support\Facades\Storage;

use function Laravel\Prompts\select;

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

    public function index(Request $request, Brand $brand){
        try {
            return Trivia::where('brand_id', $brand->id)->select('id', 'name')->get();

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'An error occurred while processing your request.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Trivia $trivia) {
        try {
             
            if (!Trivia::isAccessibleToday()) {
                return response()->json([ 'message' => 'Trivia is unavailable on Sunday.', "data" => $trivia->load('game.prizes')], 403);
            }

            return $trivia->load('game.prizes');


        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'An error occurred while processing your request.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

   

    
}
