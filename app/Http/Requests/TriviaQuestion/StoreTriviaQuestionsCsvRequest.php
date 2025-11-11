<?php

namespace App\Http\Requests\TriviaQuestion;

use Illuminate\Foundation\Http\FormRequest;

class StoreTriviaQuestionsCsvRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {

        return [
            
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:2048'], // 2MB max
    
        ];
    }
}
