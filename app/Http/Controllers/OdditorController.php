<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\OdditorEducatorPage;
use App\Models\OdditorHomePageData;
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

    
    public function getOdditorTrivia(Request $request, Brand $brand) {
        // $fullName = $request->input('full_name');
        // $email = $request->input('email');
        // $phoneNo = $request->input('phone_no');
        $brandId = $brand->id;

        $trivias = Trivia::with('questions', 'questions.choices')->where('brand_id', $brandId)->first();

        if (!$trivias) {
            return $this->sendError("No Trivia found");
        }

        return $this->sendResponse($trivias, "trivia question loaded successfuly");
    }

    public function playOdditorTrivia(Request $request, Trivia $trivia) {

        $validate = $request->validate([
            'full_name' => 'required|string',
            'email' => 'required|email',
            'phone_no' => 'required|string',
            'questions' => 'required|array',
            'questions.*.question_id' => 'required|uuid|exists:trivia_questions,id',
            'questions.*.answer_id' => 'required|uuid|exists:trivia_question_choices,id',
        ]);

        $fullName = $request->input('full_name');
        $email = $request->input('email');
        $phoneNo = $request->input('phone_no');
        $questions = $request->input('questions');

       

        $trivias = $trivia->questions;

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

        $data = OdditorUsersPoint::create([
            'full_name' => $fullName,
            'email' => $email,
            'phone_no' => $phoneNo,
            'points' => $points,
            'brand_id' => $trivia->brand_id,
            'campaign_id' => $trivia->campaign_id
        ]);


        return $this->sendResponse($data, "trivia question answers successfuly");
    }
}
