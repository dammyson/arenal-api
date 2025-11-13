<?php

namespace App\Http\Controllers;

use App\Models\Prize;
use App\Models\Trivia;
use App\Models\Campaign;
use App\Models\TriviaQuestion;
use App\Models\CampaignGamePlay;
use League\Csv\Reader;
use Illuminate\Support\Facades\DB;
use App\Models\TriviaQuestionChoice;
use Illuminate\Support\Facades\Auth;
use App\Services\Trivia\CreateService;
use App\Services\Trivia\IndexTrivaService;
use App\Services\Trivia\ShowTriviaService;
use App\Http\Requests\Trivia\StoreTriviaAnswers;
use App\Http\Requests\Trivia\StoreTriviaRequest;
use App\Services\Trivia\StoreTriviaAnswerService;
use App\Services\Trivia\TestStoreTriviaAnswerService;
use App\Http\Requests\Trivia\StoreTriviaAnswerRequest;
use App\Http\Requests\TriviaQuestion\StoreTriviaQuestionsCsvRequest;
use App\Http\Requests\TriviaQuestion\StoreTriviaQuestionsRequest;
use App\Models\ArenaAudienceReward;
use App\Models\BrandPoint;
use App\Models\Game;
use App\Services\Trivia\StoreArenaTriviaAnswerService;
use App\Services\Utility\GenerateRandomLetters;
use App\Services\Utility\GetArenaAudienceBadgeListService;
use App\Services\Utility\GetTestAudienceCurrentAndNextBadge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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

    public function uploadQuestionCsv(StoreTriviaQuestionsCsvRequest $request) {
        try {
            $filePath = $request->file('file')->store('uploads');
            $csvPath = Storage::path($filePath);

            // Read CSV file
            $csv = Reader::createFromPath($csvPath, 'r');
            $csv->setHeaderOffset(0); // Assuming the first row contains column headers


            $user = $request->user();
            $users = [];
            $errors = [];

            foreach ($csv as $index => $row) {
                // Validate required fields

                $question = TriviaQuestion::create([
                    'trivia_id' => $row['trivia_id'], //$trivia->id,
                    'question' => $row['question_name'],
                    'is_general' => $row['is_general'] ?? true,
                    'points' => $row['points'] ?? 0,
                    'duration' => $row['duration'] ?? 0,
                    'media_type' => $row['media_type'] ?? null,
                    'asset_url' => $row['asset_url'] ?? null,
                    'difficulty_level' => $row['difficulty_level'] ?? 'EASY',
                    'company_id' => $user->companies[0]->id,
                    'user_id' => $user->id,
                ]);


                $choices = [];               
    
                foreach ($row['choices'] as $choiceData) {
                    $choice = TriviaQuestionChoice::create([
                        'question_id' => $question->id,
                        'choice' => $choiceData['choice'],
                        'is_correct_choice' => $choiceData['is_correct_choice'],
                        'media_type' => $choiceData['media_type'] ?? null,
                        'asset_url' => $choiceData['asset_url'] ?? null,
                    ]);

                    $choices[] = $choice;
                }

            }
    
            $question->setRelation('choices', collect($choices));
            $createdQuestions[] = $question;
            
    
            // return response()->json(['status' => true, 'data' => new UserCollection($new_user), 'message' => 'User invitation successful'], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['status' => false, 'message' => 'Error processing CSV upload'], 500);
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

    public function questionChoices(Trivia $trivia){
        try {
            $data = $trivia->load(['questions', 'questions.choices']);

           return $data;

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'An error occurred while processing your request.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function processAnswers(Trivia $trivia, StoreTriviaAnswerRequest $request) {
        try {
            $isArena = $request->boolean('is_arena') == "true" ? true : false;
            // dd($isArena);
            if ($isArena) {
                if (!Trivia::isAccessibleToday()) {
                    return $this->sendError("TThis week’s trivia has wrapped up! 🏆.
                            Trivia returns Monday at 12:00 AM — get ready for a brand-new week of fun, learning, and friendly competition!", [], 403);
                }

                $data = (new StoreArenaTriviaAnswerService($request, $request->validated()["questions"], $trivia))->run();

            } else {
                $data = (new StoreTriviaAnswerService($request, $request->validated()["questions"], $trivia))->run();

            }
        
            return $this->sendResponse($data, "answer returned successfully");
        } catch (\Throwable $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }

      
    }

    public function processArenaTriviaAnswers(Trivia $trivia, StoreTriviaAnswerRequest $request) {
        try {
            $data = (new StoreArenaTriviaAnswerService($request, $request->validated()["questions"], $trivia))->run();
        
            return $this->sendResponse($data, "answer returned successfully");
        } catch (\Throwable $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }

      
    }

    public function testProcessAnswers(Trivia $trivia, StoreTriviaAnswerRequest $request) {
        try {
            $data = (new TestStoreTriviaAnswerService($request, $request->validated()["questions"], $trivia))->run();
        
            return $this->sendResponse($data, "answer returned successfully");
        } catch (\Throwable $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }
    }

    
    public function wordTrivia(Trivia $trivia, Request $request) {
        try {
            $points = 100;
            $brandId = $trivia->brand_id;
            
            if (!$request->is_completed) {
                return $this->sendError("sorry you did not complete the challenge", [], 500);
            }
            $audience = $request->user();
            $prize = Prize::where('is_arena', true)
                ->where('points', '<=', $points)
                ->inRandomOrder()
                ->first();
                
            // return $prize;

            // Generate a unique prize code
            $randomCode = (new GenerateRandomLetters())->randomLetters();

            // Optional: ensure the generated code is unique
            while (ArenaAudienceReward::where('prize_code', $randomCode)->exists()) {
                $randomCode = (new GenerateRandomLetters())->randomLetters();
            }

            
            if ($prize) {
                $arenaAudienceReward = ArenaAudienceReward::create([
                    'game_id' => $trivia->game_id,
                    'audience_id' => $audience->id,
                    'prize_name' => $prize->name,
                    'prize_code' => $randomCode,
                    'is_redeemed' => false
                ]);

                // $brandAudienceReward->load('prize:id,name,description');
                // $brandAudienceReward = null;
            } 


            // Start a transaction
            DB::beginTransaction();
                
                // Fetch the record and lock it for update
                $campaignGamePlay = CampaignGamePlay::where('audience_id', $audience->id)
                    ->where('is_arena', true)
                    ->lockForUpdate()  // Apply pessimistic locking
                    ->first();

                if (!$campaignGamePlay) {
                    // If no record exists, create a new one
                    $campaignGamePlay = CampaignGamePlay::create([
                        'audience_id' => $audience->id,
                        'is_arena'=> true,
                        'campaign_id' => $trivia->campaign_id,
                        'game_id' => $trivia->game_id,
                        'brand_id' => $trivia->brand_id,
                        'score' => $points,
                        'played_at' => now()
                    ]);
                    
                } else {
                    // If record exists, increment score and update played_at
                    $campaignGamePlay->score += $points;
                    $campaignGamePlay->played_at = now();
                    $campaignGamePlay->save();
                }
            

                // Commit the transaction after updates

                $audienceBrandPoint = BrandPoint::where('is_arena', true)        
                ->where("audience_id", $audience->id)
                ->first();

                if ($audienceBrandPoint) {
                    $audienceBrandPoint->points += $points;
                    $audienceBrandPoint->save();
            
                } else {
                    $audienceBrandPoint = BrandPoint::create([
                        'is_arena' => true,
                        'audience_id' => $audience->id,
                        'brand_id' => $brandId,
                        'points' => $points
                    ]);
                }

                // [$currentBadge, $nextBadge] = (new GetAudienceCurrentAndNextBadge($brandId, $audienceBrandPoint->points))->run();
                [$currentBadge, $nextBadge] = (new GetTestAudienceCurrentAndNextBadge($brandId, $audienceBrandPoint->points, true))->run();

                $audienceBadgesList = (new GetArenaAudienceBadgeListService($brandId, $audience->id, $audienceBrandPoint->points, true))->run();
        
            DB::commit();

              
               

            $data = [
                "arena_audience_reward" => $arenaAudienceReward,
                // "leaderboard" => $campaignGamePlay,
                "current_badge" => $currentBadge,
                "next_badge" => $nextBadge,
                // "audience_badges_list" => $audienceBadgesList
            ];
            return $this->sendResponse($data, "trivia reward allocated successfully");
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