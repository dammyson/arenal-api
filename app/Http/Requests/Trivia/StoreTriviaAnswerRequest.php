<?php

namespace App\Http\Requests\Trivia;

use Illuminate\Foundation\Http\FormRequest;

class StoreTriviaAnswerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    { 
        return [
            "questions" => "array|required",
            "questions.*.question_id" => "required|uuid|exists:trivia_questions,id|distinct",
            "questions.*.answer_id" => "required|uuid|exists:trivia_question_choices,id|distinct",
            "brand_id" => "required|exists:brands,id",
            "campaign_id" => "required|exists:campaigns,id"
        ];
    }
}
