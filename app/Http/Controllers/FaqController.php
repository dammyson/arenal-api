<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends BaseController
{
    
    public function createFaq(Request $request)
    {
        try {
            
            $audience = $request->user();
            // Validate the request
            $request->validate([
                'question' => 'required|string|max:255',
                // 'answer' => 'required|string'
            ]);

            // Create the FAQ entry
            $data = Faq::create([
                'question' => $request->input('question'),
                // 'answer' => $request->input('answer'),
                'audience_id' => $audience->id
            ]);

            return $this->sendResponse($data, "FAQ created successfully");

        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        

    }

    public function getFaq()
    {
        try {
            $data = Faq::where('is_faq', true)->get();
            
            return $this->sendResponse($data, "FAQ retrieved successfully");


        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }  
    }

    public function updateFaq(Request $request, Faq $faq)
    {
        try {
            // Validate the request
            $request->validate([
                'is_faq' => 'required|boolean'
            ]);
    
            // Create the FAQ entry
            $faq->is_faq = $request->input("is_faq");
            $faq->save();
            
            return $this->sendResponse($faq, "FAQ updated successfully");

        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        } 

    }

    public function answerFaq(Request $request, Faq $faq)
    {
        try {
            $user = $request->user();
            // Validate the request
            $request->validate([
                'answer' => 'required|string'
            ]);

            // Create the FAQ entry
            $faq->answer = $request->input("answer");
            $faq->user_id = $user->id;
            $faq->save();

            return $this->sendResponse($faq, "FAQ updated successfully");

        } catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        } 
        
    }
}
