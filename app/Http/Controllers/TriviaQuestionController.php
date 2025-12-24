<?php

namespace App\Http\Controllers;

use App\Http\Requests\Trivia\ProcessWordTriviaRequest;
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
use App\Services\Utility\CheckDailyBonusService;
use App\Services\Utility\GenerateRandomLetters;
use App\Services\Utility\GetArenaAudienceBadgeListService;
use App\Services\Utility\GetTestAudienceCurrentAndNextBadge;
use App\Services\Utility\LeaderboardService;
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

            
            $brand = $trivia->brand;
            // dd(now()->format('l'));

            if ($brand["closes_on"] == now()->format('l')) {
                return response()->json([ 'message' => 'This week’s trivia has wrapped up! 🏆.
                    Trivia returns Monday at 12:00 AM — get ready for a brand-new week of fun, learning, and friendly competition!', "data" => $trivia->load('game.prizes')], 403);
            
            }
           
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
            $isArena = $request->boolean('is_arena') == "true" ? true : false;

            if (count($request->validated()["questions"]) < 1) {
                return $this->sendError("Submitted Answers has no content", [], 422);
            } 
            // dd($isArena);
            if ($isArena) {
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

            if (count($request->validated()["questions"]) < 1) {
                return $this->sendError("Submitted Answers has no content", [], 422);
            } 
            $data = (new StoreArenaTriviaAnswerService($request, $request->validated()["questions"], $trivia))->run();
        
            return $this->sendResponse($data, "answer returned successfully");
        } catch (\Throwable $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }

      
    }


    
    public function wordTrivia(Trivia $trivia, ProcessWordTriviaRequest $request) {
        try {
            // $points = 100;
            $brandId = $trivia->brand_id;
            $gameId = $trivia->game_id;
            $campaignId = $trivia->campaign_id;

            $percentageOfCompletedWords = $request->input('percentage_of_completion');

            $totalNoOfWords = $request->input('total_no_of_words');
         

            $points = (int) floor(($percentageOfCompletedWords * $totalNoOfWords) * 2);
            // dd($points);
            // dd($request->input('is_completed'));

            if ($percentageOfCompletedWords == 1) {
                $points += 100;
            }

            // dump($points);
            $totalPoints = $points;
           
            $audience = $request->user();  
            
            [ $isHighScore, $highScoreBonus ] = (new CheckDailyBonusService())->checkHighScore($audience->id, $totalPoints, $brandId, false);
            
            if ($isHighScore) {
                // dd($highScoreBonus);
               
                $totalPoints += $highScoreBonus; 
            }
            
            [$eligibilityStatus, $bonusId] = (new CheckDailyBonusService())->checkEligibility($brandId, $gameId, $audience->id, true);
            // dd($eligibilityStatus);
            
            if ($eligibilityStatus == true) {
                $dailyBonus = (new CheckDailyBonusService())->allocatedDailyBonus($bonusId, $audience->id, $brandId, $gameId, true);
                $totalPoints += $dailyBonus;
            }
            // dump($points);
            // Start a transaction
             // Start a transaction
        DB::beginTransaction();
            $campaignGamePlay = (new LeaderboardService())->storeLeaderboard($audience->id, $campaignId, $gameId, $brandId,  true, $totalPoints);
            
        // Commit the transaction after updates
        DB::commit();
            

                // Commit the transaction after updates

                $audienceBrandPoint = BrandPoint::where('is_arena', true)        
                ->where("audience_id", $audience->id)
                ->first();

                if ($audienceBrandPoint) {
                    $audienceBrandPoint->points += $totalPoints;
                    $audienceBrandPoint->save();
            
                } else {
                    $audienceBrandPoint = BrandPoint::create([
                        'is_arena' => true,
                        'audience_id' => $audience->id,
                        'brand_id' => $brandId,
                        'points' => $totalPoints
                    ]);
                }

                // [$currentBadge, $nextBadge] = (new GetAudienceCurrentAndNextBadge($brandId, $audienceBrandPoint->points))->run();
                [$currentBadge, $nextBadge] = (new GetTestAudienceCurrentAndNextBadge($brandId, $audienceBrandPoint->points, true))->run();

                $audienceBadgesList = (new GetArenaAudienceBadgeListService($brandId, $audience->id, $audienceBrandPoint->points, true))->run();
        
            DB::commit();

              
               

            $data = [
                "audience_point" => $audienceBrandPoint?->points,
                // "leaderboard" => $campaignGamePlay,
                "points" => $points,
                "total_points" => $totalPoints,
                "current_badge" => $currentBadge,
                "next_badge" => $nextBadge,
                "daily_bonus" => $dailyBonus ?? null,
                'high_score_bonus' => $highScoreBonus ?? null,
                "audience_badges_list" => $audienceBadgesList
            ];
            return $this->sendResponse($data, "trivia reward allocated successfully");
        } catch (\Throwable $e) {

            DB::rollBack();
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