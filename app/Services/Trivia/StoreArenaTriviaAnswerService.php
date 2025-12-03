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
use App\Models\ArenaAudienceReward;
use App\Models\ArenaCampaignGamePlay;
use App\Models\Brand;
use App\Services\Utility\CheckDailyBonusService;
use App\Services\Utility\GetArenaAudienceBadgeListService;
use App\Services\Utility\GetAudienceCurrentAndNextBadge;
use App\Services\Utility\GetTestAudienceCurrentAndNextBadge;

class StoreArenaTriviaAnswerService implements BaseServiceInterface
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
        // dd($brands);
        
        $audience = $this->request->user();
        // dd($this->trivia);
        $brandId = $this->trivia->brand_id;
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

        
              
        [$eligibilityStatus, $bonusId] = (new CheckDailyBonusService())->checkEligibility($brandId, $audience->id, true);
            // dd($eligibilityStatus);
            
        if ($eligibilityStatus == true) {
                $dailyBonus = (new CheckDailyBonusService())->allocatedDailyBonus($bonusId, $audience->id, $brandId, true);
                $points += $dailyBonus;
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
                        'campaign_id' => $campaignId,
                        'game_id' => $gameId,
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
        
        // $points = 100;
        $prize = Prize::where('is_arena', true)
            ->where('points', '<=', $points)
            ->inRandomOrder()
            ->first();
            
        // return $prize;
        
        if ($prize) {
            $brandAudienceReward = ArenaAudienceReward::create([
                'game_id' => $gameId,
                'audience_id' => $audience->id,
                'prize_name' => $prize->name,
                'prize_code' => $this->generatePrizeCode(),
                'is_redeemed' => false
            ]);

            // $brandAudienceReward->load('prize:id,name,description');
            // $brandAudienceReward = null;
        } 
        
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
            
        return [
            "total_questions_count" => $totalQuestionsCount, 
            "correct_answers_count" => $correctAnswersCount, 
            'current_badge' => $currentBadge,
            'next_badge' => $nextBadge,
            "audience_points" => $audienceBrandPoint->points,
            "quiz_point" => $points, 
            "reward" => $brandAudienceReward ?? null, 
            'daily_bonus' => $dailyBonus ?? null,
            "audience_badges_list" => $audienceBadgesList, 
            "leaderboard" => $campaignGamePlay 
        ];
    }

    private function generatePrizeCode($length = 8)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $referralId = '';
        for ($i = 0; $i < $length; $i++) {
            $referralId .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $referralId;
    }

    
}