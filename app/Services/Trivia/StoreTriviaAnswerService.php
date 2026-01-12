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
use App\Models\ArenaCampaignGamePlay;
use App\Models\Game;
use App\Services\Utility\CheckDailyBonusService;
use App\Services\Utility\GetAudienceCurrentAndNextBadge;
use App\Services\Utility\LeaderboardService;

class StoreTriviaAnswerService implements BaseServiceInterface
{
    protected $request;
    protected $questions;
    protected $trivia;

    public function __construct (StoreTriviaAnswerRequest $request, $questions, Trivia $trivia)
    {
        $this->request = $request;
        $this->questions = $questions;
        $this->trivia = $trivia;
    }

    public function run()
    {
        $audience = $this->request->user();
        // dd($audience);
        $brandId = $this->trivia->brand_id;
        // dd($brandId);
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
                // dd($triviaQuestion->points);
               $points += $triviaQuestion->points;
            }
        }
        // dump($points);
        $totalPoints = $points;
      
       [ $isHighScore, $highScoreBonus ] = (new CheckDailyBonusService())->checkHighScore($audience->id, $totalPoints, $brandId, false);

        if ($isHighScore) {
            $totalPoints += $highScoreBonus; 
        }

        // dump($points);
        [$eligibilityStatus, $bonusId] = (new CheckDailyBonusService())->checkEligibility($brandId, $gameId,$audience->id, false);
        // dd($eligibilityStatus);
        if ($eligibilityStatus == true) {
            $dailyBonus = (new CheckDailyBonusService())->allocatedDailyBonus($bonusId, $audience->id, $brandId, $gameId, false);
            $totalPoints += $dailyBonus;
        }
        // dd($points);
        // Start a transaction
        DB::beginTransaction();
            $campaignGamePlay = (new LeaderboardService())->storeLeaderboard($audience->id, $campaignId, $gameId, $brandId,  false, $totalPoints);
            
        // Commit the transaction after updates
        DB::commit();
    
        
        $audienceBrandPoint = BrandPoint::where('brand_id', $brandId)        
            ->where("audience_id", $audience->id)
            ->first();

        if ($audienceBrandPoint) {
            $audienceBrandPoint->points += $totalPoints;
            $audienceBrandPoint->save();
       
        } else {
            $audienceBrandPoint = BrandPoint::create([
                'brand_id' => $brandId,
                'audience_id' => $audience->id,
                'points' => $totalPoints
            ]);
        }

        [$currentBadge, $nextBadge] = (new GetAudienceCurrentAndNextBadge($brandId, $audienceBrandPoint->points))->run();

        $audienceBadgesList = (new GetAudienceBadgeListService($brandId, $audience->id, $audienceBrandPoint->points))->run();
            
        return [
            "total_questions_count" => $totalQuestionsCount, 
            "correct_answers_count" => $correctAnswersCount, 
            'current_badge' => $currentBadge,
            'next_badge' => $nextBadge,
            'total_points' => $totalPoints,
            'high_score_bonus' => $highScoreBonus ?? null,
            'daily_bonus' => $dailyBonus ?? null,
            "audience_points" => $audienceBrandPoint->points,
            "quiz_point" => $points, 
            // "reward" => $brandAudienceReward ?? null, 
            "audience_badges_list" => $audienceBadgesList, 
            "leaderboard" => $campaignGamePlay 
        ];
    }

    
}