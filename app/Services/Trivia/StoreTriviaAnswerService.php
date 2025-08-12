<?php

namespace App\Services\Trivia;

use App\Models\Badge;
use App\Models\Prize;
use App\Models\Trivia;
use App\Models\BrandPoint;
use App\Models\AudienceBadge;
use App\Models\TriviaQuestion;
use App\Models\CampaignGamePlay;
use Illuminate\Support\Facades\DB;
use App\Models\BrandAudienceReward;
use App\Models\TriviaQuestionChoice;
use App\Services\BaseServiceInterface;
use App\Services\Utility\GetAudienceBadgeListService;
use App\Http\Requests\Trivia\StoreTriviaAnswerRequest;

class StoreTriviaAnswerService implements BaseServiceInterface
{
    protected $request;
    protected $questions;
    protected $gameId;
    protected $prizeId;
    protected $trivia;

    public function __construct(StoreTriviaAnswerRequest $request, $questions, $prizeId, Trivia $trivia)
    {
        $this->request = $request;
        $this->questions = $questions;
        $this->gameId = $trivia->game_id;
        $this->prizeId = $prizeId;
        $this->trivia = $trivia;
    }

    public function run()
    {
        $audience = $this->request->user();
        // dd($audience);
        $brandId = $this->request->brand_id;
        $campaignId = $this->request->campaign_id;

        // dd($trivia->game_id);
        $points = 0;

        $totalQuestionsCount = $this->trivia->questions()->count();

        $correctAnswersCount = 0;
        foreach($this->questions as $question) {
            $triviaQuestionChoice = TriviaQuestionChoice::where("question_id", $question["question_id"])
                ->where('id', $question["answer_id"])->first();

            // dd($triviaQuestionChoice);

            if ($triviaQuestionChoice->is_correct_choice) {
                $correctAnswersCount += 1;
               $triviaQuestion = TriviaQuestion::find( $question["question_id"]);
               $points += $triviaQuestion->points;
            }
        }

        // Start a transaction
        DB::beginTransaction();

            // Fetch the record and lock it for update
            $campaignGamePlay = CampaignGamePlay::where('audience_id', $audience->id)
                ->where('brand_id', $brandId)
                ->where('campaign_id', $campaignId)
                ->where('game_id', $this->gameId)
                ->lockForUpdate()  // Apply pessimistic locking
                ->first();

            if (!$campaignGamePlay) {
                // If no record exists, create a new one
                $campaignGamePlay = CampaignGamePlay::create([
                    'audience_id' => $audience->id,
                    'campaign_id' => $campaignId,
                    'game_id' => $this->gameId,
                    'brand_id' => $brandId,
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
        DB::commit();
        
        $prize = Prize::where('id', $this->prizeId)->first();

        if ($points >= $prize->points) {
            $brandAudienceReward = BrandAudienceReward::create([
                'brand_id' => $brandId,
                'audience_id' => $this->request->user()->id,
                'prize_id' => $this->prizeId,
                'is_redeemed' => false
            ]);
        } 
        
        $audienceBrandPoint = BrandPoint::where('brand_id', $brandId)        
            ->where("audience_id", $audience->id)
            ->first();

        if ($audienceBrandPoint) {
            $audienceBrandPoint->points += $points;
            $audienceBrandPoint->save();
       
        } else {
            $audienceBrandPoint = BrandPoint::create([
                'brand_id' => $brandId,
                'audience_id' => $audience->id,
                'points' => $points
            ]);
        }

        $audienceBadgesList = (new GetAudienceBadgeListService($brandId, $audience->id, $audienceBrandPoint->points))->run();
            
        return ["total_questions_count" => $totalQuestionsCount, "correct_answers_count" => $correctAnswersCount, "points" => $points, "audience_badges_list" => $audienceBadgesList, "leaderboard" => $campaignGamePlay ];
    }

    
}