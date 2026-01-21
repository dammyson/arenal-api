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
use App\Models\CampaignGame;
use App\Models\CampaignGameRule;
use App\Services\Utility\IndexUtils;
use Illuminate\Support\Facades\DB;
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

    public function show(Request $request, Trivia $trivia) {
        try {

             $brand = $trivia->brand;
            // dd(now()->format('l'));

            $isOpen = (new IndexUtils())->checkCampaignCapacity($trivia->campaign_id);
            // dd($isOpen);
            if (!$isOpen) {
                return $this->sendError("Campaign Filled. Sorry you cant join this campaign", [], 500);
            }

            if ($brand["closes_on"] == now()->format('l')) {
                return response()->json([ 'message' => 'This week’s trivia has wrapped up! 🏆.
                    Trivia returns Monday at 12:00 AM — get ready for a brand-new week of fun, learning, and friendly competition!', "data" => $trivia->load('game.prizes')], 403);
            
            }
            
            return $trivia->load('game.prizes');


        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'An error occurred while processing your request.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function deleteTrivias() {
        try {
            DB::transaction(function () {

            $trivia = Trivia::with(['questions.choices', 'game'])
                ->where('id', 'a0ddf9d2-6d00-441c-9747-5b28816ee598')
                ->firstOrFail();

            // Delete choices → questions
            foreach ($trivia->questions as $question) {
                $question->choices()->delete();
            }

            $trivia->questions()->delete();

            // Delete trivia
            $trivia->delete();

            if ($trivia->game) {
                CampaignGame::where('game_id', $trivia->game->id)->delete();
                CampaignGameRule::where('game_id', $trivia->game->id)->delete();

                $trivia->game->delete();
            }
            });

            return response()->json([ 'message' => 'Trivia deleted successfully'], 200);


        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'An error occurred while processing your request.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

   

    
}
