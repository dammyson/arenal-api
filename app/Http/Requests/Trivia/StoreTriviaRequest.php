<?php

namespace App\Http\Requests\Trivia;

use Illuminate\Foundation\Http\FormRequest;

class StoreTriviaRequest extends FormRequest
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
            "trivia_name" => "required|string",
            "game_id" => "required|exists:games,id",
            "brand_id" => "required|exists:brands,id",
            "image_url" => "sometimes|string",
            "entry_fee" => "sometimes|integer",
            "point_score" => "sometimes|integer",
            "high_score_bonus" => "sometimes|integer",
        ];
    }
}
