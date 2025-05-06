<?php

namespace App\Http\Requests\TriviaQuestion;

use Illuminate\Foundation\Http\FormRequest;

class StoreTriviaQuestionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.is_general' => 'boolean',
            'questions.*.points' => 'numeric|min:0',
            'questions.*.duration' => 'numeric|min:0',
            'questions.*.media_type' => 'nullable|in:image,audio,video',
            'questions.*.asset_url' => 'nullable|string',
            'questions.*.difficulty_level' => 'in:EASY,MEDIUM,HARD',

            'questions.*.choices' => 'required|array|min:2',
            'questions.*.choices.*.choice' => 'required|string',
            'questions.*.choices.*.is_correct_choice' => 'required|boolean',
            'questions.*.choices.*.media_type' => 'nullable|in:image,audio,video',
            'questions.*.choices.*.asset_url' => 'nullable|string',
        ];
    }
}
