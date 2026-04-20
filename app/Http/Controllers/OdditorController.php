<?php

namespace App\Http\Controllers;

use App\Http\Resources\CampaignParticipantResource;
use App\Http\Resources\OdditorParticipantResource;
use App\Models\Brand;
use App\Models\Campaign;
use App\Models\CampaignParticipant;
use App\Models\CampaignReengagement;
use App\Models\OdditorEducatorPage;
use App\Models\OdditorHomePageData;
use App\Models\OdditorReengagementStats;
use App\Models\OdditorUsersPoint;
use App\Models\Trivia;
use App\Models\TriviaQuestion;
use App\Models\TriviaQuestionChoice;
use Illuminate\Http\Request;

class OdditorController extends BaseController
{
    public function storeOdditorHomeData(Request $request) {
        try {
            $validated = $request->validate([
                "brand_id" => "required|uuid|exists:brands,id",
                "title" => "required|string|max:255",
                "subtitle" => "required|string|max:255",
                "description" => "required|string|max:255",
                "primary_color" => "required|string|max:255",
                "secondary_color" => "required|string|max:255",
                "btn_text" => "required|string|max:255"
            ]);

            $data = OdditorHomePageData::create([  
                'brand_id' => $validated['brand_id'], 
                'title' => $validated['title'],
                'subtitle' => $validated['subtitle'],
                'description' => $validated['description'],
                'primary_color' => $validated['primary_color'],
                'secondary_color' => $validated['secondary_color'],
                'btn_text' => $validated['btn_text']
            ]);

            return $this->sendResponse($data, "home page data added successfully");
        } catch (\Exception $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }
    }

    public function storeOdditorEducationPageData(Request $request) {
        try {

            $validated = $request->validate([
                "brand_id" => "required|uuid|exists:brands,id",
                "title" => "required|string|max:255",
                "description" => "required|string|max:255",
                "audit_count" => "required|numeric",
                "overcharge_count" => "required|numeric",
                "cities_served" => "required|numeric",
                "button_header_text" => "required|string",
                "button_subheader_text" => "required|string",
            ]);

            $data = OdditorEducatorPage::create([  
                'brand_id' => $validated['brand_id'], 
                'title' => $validated['title'],
                'description' => $validated['description'],
                'audit_count' => $validated['audit_count'],
                'overcharge_count' => $validated['overcharge_count'],
                'cities_served' => $validated['cities_served'],
                'button_header_text' => $validated['button_header_text'],
                'button_subheader_text' => $validated['button_subheader_text']
            ]);

            return $this->sendResponse($data, "home page data added successfully");
        } catch (\Exception $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }
    }
    public function storeFeedChoice(Request $request) {
        try {

            $validated = $request->validate([
                "choices" => "required|array",
                "choices.*.choice_id" => "required|uuid|exists:trivia_question_choices,id",
                "choices.*.choice_feedback" => "nullable|string"
            ]);

            $data = [];
            
            foreach ($validated["choices"] as $choice) {

                $foundChoice = TriviaQuestionChoice::where("id", $choice['choice_id'])->first();

                if ($foundChoice) {
                    $foundChoice->feedback = $choice['choice_feedback'] ?? null;
                    $foundChoice->save();
                    $data[] = $foundChoice;
                }
            }

            return $this->sendResponse($data, "Feedback updated successfully");

        } catch (\Exception $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }
    }

    public function odditorHomePage(Request $request, Brand $brand) {
        try {
            $data = OdditorHomePageData::where('brand_id', $brand->id)->first();

            return $this->sendResponse($data, "home page data retrieved successfully");
        } catch (\Exception $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }

    }
    public function odditorEducationPage(Request $request, Brand $brand) {
        try {
            $data = OdditorEducatorPage::where('brand_id', $brand->id)->first();

            return $this->sendResponse($data, "home page data retrieved successfully");
        } catch (\Exception $e) {
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }

    }

    
    public function oldGetOdditorTrivia(Request $request, Brand $brand) {
      
        $brandId = $brand->id;

        $trivia = Trivia::with('questions', 'questions.choices')->where('brand_id', $brandId)->first();

        if (!$trivia) {
            return $this->sendError("No Trivia found");
        }

       

        return $this->sendResponse($trivia, "trivia question loaded successfuly");
    }
    
    public function getOdditorTrivia(Request $request, Brand $brand) {
        $fullName = $request->input('full_name');
        $email = $request->input('email');
        $phoneNo = $request->input('phone_no');
        $deviceType = $request->input('device_type');
        $brandId = $brand->id;
        $location = $request->input('location');

        // $trivia = Trivia::with('questions', 'questions.choices')->where('brand_id', $brandId)->first();

        $trivia = Trivia::with([
            'questions' => function ($q) {
                $q->orderBy('position', 'asc');
            },
            'questions.choices' => function ($q) {
                $q->orderBy('position', 'asc');
            }
        ])
        ->where('brand_id', $brandId)
        ->first();
        if (!$trivia) {
            return $this->sendError("No Trivia found");
        }

        // $odditorUser = OdditorUsersPoint::where('email', $email)->first();

        // if ($odditorUser) {
        //     if ($odditorUser->status == "in_progress") {
        //         OdditorReengagementStats::where("email", $email)->update([
        //             'still_in_progress_after_return' => true
        //         ]);
        //     }
        // }

        CampaignParticipant::updateOrCreate(
            [
                'email' => $email,
                'campaign_id' => $trivia->campaign_id,
                'brand_id' => $trivia->brand_id,
            ],
            [
                'full_name' => $fullName,
                'phone_no' => $phoneNo,
                'points' => 0,
                'status' => "in_progress",
                'location' => $location,
                'started_at' => now(),
                'device_type' => $deviceType
            ]
        );
        


        return $this->sendResponse($trivia, "trivia question loaded successfuly");
    }

    public function playOdditorTrivia(Request $request, Trivia $trivia) {

        $validate = $request->validate([
            'full_name' => 'required|string',
            'email' => 'required|email',
            'phone_no' => 'required|string',
            'status' => "required|string:in_progress,abandoned",
            'questions' => 'sometimes|array',
            'questions.*.question_id' => 'required|uuid|exists:trivia_questions,id',
            'questions.*.answer_id' => 'required|uuid|exists:trivia_question_choices,id',
        ]);



        $fullName = $request->input('full_name');
        $email = $request->input('email');
        $phoneNo = $request->input('phone_no');
        $questions = $request->input('questions');
        $brandId = $trivia->brand_id;
        $campaignId = $trivia->campaign_id;


        $campParticipants = CampaignParticipant::where("email", $email)->where("brand_id", $brandId)->where("campaign_id", $campaignId)->first();
        if (!$campParticipants) {
            return $this->sendError("user not found");
        }
        $campPartEngagemnet = null;
        if ($campParticipants->status == "abandoned") {
            $campPartEngagemnet = CampaignReengagement::create([
                'email' => $email,
                'abandoned_then_returned' => true,
                'brand_id' => $brandId,
                'campaign_id' => $campaignId
            ]);  
        }
       
        if ($validate["status"] == "abandoned") {
            
            if ($campPartEngagemnet)  {
                $campParticipants->status = "abandoned";
                $campParticipants->ended_at = now();

                $campParticipants->save();
                
                return $this->sendResponse($campParticipants, "quiz abandoned");
              
            }
            return $this->sendError("user not found");
        }

       

         // dd($trivia->game_id);
        $points = 0;

        $totalQuestionsCount = 10;

        $correctAnswersCount = 0;

     
        foreach($questions as $question) {
            $triviaQuestionChoice = TriviaQuestionChoice::where("question_id", $question["question_id"])
                ->where('id', $question["answer_id"])->first();

            // dd($triviaQuestionChoice);

            if ($triviaQuestionChoice->is_correct_choice) {
                $correctAnswersCount += 1;
                $triviaQuestion = TriviaQuestion::find( $question["question_id"]);
                $points += $triviaQuestion->points;
            }
        }

       $campParticipants->points = $points;
       $campParticipants->status = 'in_progress';
       $campParticipants->ended_at = now();
       $campParticipants->save();




        return $this->sendResponse($campParticipants, "trivia question answers successfuly");
    }

    public function completedOdditor(CampaignParticipant $campParticipant) {

        if ($campParticipant->status == "abandoned") {
            CampaignReengagement::create([
                'email' => $campParticipant->email,
                'abandoned_then_returned' => true,
                'brand_id' => $campParticipant->brand_id,
                'campaign_id' => $campParticipant->campaign_id,
                'campaign_participant_id' => $campParticipant->id
            ]);
        }
        $campParticipant->status = "completed";
        $campParticipant->save();

        return $this->sendResponse($campParticipant, "campaign status updated successfully");
    }

    public function cardData(Request $request, Campaign $campaign) {

        $totalParticipants = CampaignParticipant::where('campaign_id', $campaign->id)->count();
        $totalCompleted = CampaignParticipant::where('campaign_id', $campaign->id)->where('status', 'completed')->count();
        $completedPercentage = $totalParticipants > 0  ? round((($totalCompleted / $totalParticipants) * 100), 2) : 0;
      
        $totalInProgress = CampaignParticipant::where('campaign_id', $campaign->id)->where('status', 'in_progress')->count();
        $totalInProgressPercentage =  $totalInProgress > 0 ?  round((( $totalInProgress  / $totalParticipants) * 100),2) : 0;
       
        $totalAbandoned = CampaignParticipant::where('campaign_id', $campaign->id)->where('status', 'abandoned')->count();
        $totalAbandonedPercentage =  $totalAbandoned > 0 ?  round((( $totalAbandoned / $totalParticipants) * 100),2) : 0;

        // $sumStartTime = CampaignParticipant::where('campaign_id', $campaign->id)->where('status', 'completed')->sum('started_at');
        // $sumEndTime = CampaignParticipant::where('campaign_id', $campaign->id)->where('status', 'completed')->sum('ended_at');

        // $avgCompletedTime = CampaignParticipant::where('campaign_id', $campaign->id)
        //     ->where('status', 'completed')
        //     ->whereNotNull('started_at')
        //     ->whereNotNull('ended_at')
        //     ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, started_at, ended_at)) as avg_time')
        //     ->value('avg_time');

        $avgCompletedTime = CampaignParticipant::where('campaign_id', $campaign->id)
            ->where('status', 'completed')
            ->whereNotNull('started_at')
            ->whereNotNull('ended_at')
            ->whereRaw('ended_at >= started_at') 
            ->whereRaw('TIMESTAMPDIFF(SECOND, started_at, ended_at) < 180') 
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, started_at, ended_at)) as avg_time')
            ->value('avg_time');
        

        if ($avgCompletedTime < 1) {
            $avgCompletedTime = CampaignParticipant::where('campaign_id', $campaign->id)
                ->where('status', 'completed')
                ->whereNotNull('started_at')
                ->whereNotNull('ended_at')
                ->whereRaw('ended_at >= started_at') 
                ->whereRaw('TIMESTAMPDIFF(SECOND, started_at, ended_at) < 180') 
                ->selectRaw('AVG(TIMESTAMPDIFF(SECOND, started_at, ended_at)) as avg_time')
                ->value('avg_time');
            $avgCompletedTime = $avgCompletedTime . " sec";
        } else {
            $avgCompletedTime = $avgCompletedTime . " mins";
        }

        $abandonAndReturned = CampaignReengagement::where('abandoned_then_returned', true)->count();

        $data = [
            'total_participants' => $totalParticipants,
            'total_completed' => $totalCompleted,
            'total_completed_percentage' => $completedPercentage,
            'total_in_progress' => $totalInProgress,
            'total_in_progress_percentage' => $totalInProgressPercentage,
            'total_abandoned' => $totalAbandoned,
            'total_abandoned_percentage' => $totalAbandonedPercentage,
            'avg_completion_time' => $avgCompletedTime,
            'abandon_and_returned' => $abandonAndReturned

        ];

        return $this->sendResponse($data, "card data retrieved");
    }

    public function getOdditorParticipants(Request $request , Campaign $campaign) {
        $request->validate([
            'filter' => ['nullable', 'in:completed,in_progress,abandoned'],
        ]);
        $filter = $request->query('filter');
        try {
            $participantList =  CampaignParticipant::where('campaign_id', $campaign->id)->when( $filter,
                fn ($query) => $query->where('status', $filter)
            )
            ->orderBy('created_at', 'desc')
            ->get();

            $totalParticipants = $participantList->count();
            $totalAbandoned = CampaignParticipant::where('campaign_id', $campaign->id)->where('status', 'abandoned')->count();
            $totalInProgress = CampaignParticipant::where('campaign_id', $campaign->id)->where('status', 'in_progress')->count();
            $totalCompleted = CampaignParticipant::where('campaign_id', $campaign->id)->where('status', 'completed')->count();

            $data = CampaignParticipantResource::collection($participantList);
            
            $updatedData = [
                "participants" => $data,
                "total_abandoned" => $totalAbandoned,
                "total_completed" =>  $totalCompleted,
                "total_in_progress" =>  $totalInProgress
            ];
            return $this->sendResponse($updatedData, "participant retrieved successfully");

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), [], 500);
        }
    }
}
