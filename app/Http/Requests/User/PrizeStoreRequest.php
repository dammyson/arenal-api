<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class PrizeStoreRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'game_id' => ['sometimes', 'string'],
            'brand_id' => ['required', 'string'],
            'campaign_id' => ['required', 'string'],
            'description' => ['required', 'string'],
            'image_url' => ["required|string"]
           
        ];
    }
}
