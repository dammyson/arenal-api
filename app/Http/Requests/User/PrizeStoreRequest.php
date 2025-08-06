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
            'game_id' => ['sometimes', 'uuid', 'exists:games,id'],
            'brand_id' => ['required', 'uuid', 'exists:brands,id'],
            'campaign_id' => ['required', 'uuid', 'exists:campaigns,id'],
            'description' => ['required', 'string'],
            'image_url' => ["required", "string"],
            'points' => ['required', 'numeric']
           
        ];
    }
}
