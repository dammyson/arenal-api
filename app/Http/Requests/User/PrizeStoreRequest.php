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
            'game_id' => ['sometimes', 'uuid', 'exists:games,id'],
            'brand_id' => ['sometimes', 'uuid', 'exists:brands,id'],
            'campaign_id' => ['sometimes', 'uuid', 'exists:campaigns,id'],
            "prizes" => "required|array",
            'prizes.*.name' => ['required', 'string'],
            'prizes.*.description' => ['required', 'string'],
            'prizes.*.image_url' => ["required", "string"],
            'prizes.*.points' => ['required', 'numeric'],
            'prizes.*.amount' => ['sometimes', 'numeric'],
            'prizes.*.quantity' => ['sometimes', 'numeric'],
            'prizes.*.is_arena' => ['required', 'boolean']
           
        ];
    }
}
