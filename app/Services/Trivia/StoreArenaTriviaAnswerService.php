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
use App\Models\ArenaCampaignGamePlay;
use App\Services\BaseServiceInterface;
use App\Services\Utility\GetAudienceBadgeListService;
use App\Http\Requests\Trivia\StoreTriviaAnswerRequest;
use App\Services\Utility\GetAudienceCurrentAndNextBadge;
use App\Services\Utility\GetArenaAudienceBadgeListService;
use App\Services\Utility\GetArenaAudienceCurrentAndNextBadge;

class StoreTriviaArenaAnswerService implements BaseServiceInterface
{
    protected $request;
    protected $questions;
    protected $trivia;

    public function __construct(StoreTriviaAnswerRequest $request, $questions, Trivia $trivia)
    {
        $this->request = $request;
        $this->questions = $questions;
        $this->trivia = $trivia;
    }

    public function run()
    {
        $audience = $this->request->user();
        // dd($audience);
        $campaignId = $this->trivia->campaign_id;
        $gameId = $this->trivia->game_id;

        // dd($trivia->game_id);
        $points = 0;

        $totalQuestionsCount = 10;

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
            $campaignGamePlay = ArenaCampaignGamePlay::where('audience_id', $audience->id)
                ->where('campaign_id', $campaignId)
                ->where('game_id', $gameId)
                ->lockForUpdate()  // Apply pessimistic locking
                ->first();

            if (!$campaignGamePlay) {
                // If no record exists, create a new one
                $campaignGamePlay = ArenaCampaignGamePlay::create([
                    'audience_id' => $audience->id,
                    'campaign_id' => $campaignId,
                    'game_id' => $gameId,
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
        
        // $points = 100;
        $prize = ArenaPrize::where('points', '<=', $points)
            ->inRandomOrder()
            ->first();
            
        // return $prize;
        
        if ($prize) {
            $brandAudienceReward = ArenaBrandAudienceReward::create([
                'audience_id' => $audience->id,
                'prize_id' => $prize->id,
                'is_redeemed' => false
            ]);

            $brandAudienceReward->load('prize:id,name,description');
            // $brandAudienceReward = null;
        } 
        
        $audiencePoint = ArenaPoint::where("audience_id", $audience->id)
            ->first();

        if ($audiencePoint) {
            $audiencePoint->points += $points;
            $audiencePoint->save();
       
        } else {
            $audienceBrandPoint = ArenaPoint::create([
                'audience_id' => $audience->id,
                'points' => $points
            ]);
        }

        [$currentBadge, $nextBadge] = (new GetArenaAudienceCurrentAndNextBadge($audienceBrandPoint->points))->run();

        $audienceBadgesList = (new GetArenaAudienceBadgeListService($audience->id, $audienceBrandPoint->points))->run();
            
        return [
            "total_questions_count" => $totalQuestionsCount, 
            "correct_answers_count" => $correctAnswersCount, 
            'current_badge' => $currentBadge,
            'next_badge' => $nextBadge,
            "audience_points" => $audienceBrandPoint->points,
            "quiz_point" => $points, 
            "reward" => $brandAudienceReward ?? null, 
            "audience_badges_list" => $audienceBadgesList, 
            "leaderboard" => $campaignGamePlay 
        ];
    }

    
}