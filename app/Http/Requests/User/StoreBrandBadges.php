<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreBrandBadges extends FormRequest
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
            "badges" => "required|array",
            "badges.*.name" => "required|string",
            "badges.*.brand_id" => "required|exists:brands,id",
            "badges.*.image_url" => "required|string",
            "badges.*.points" => "required|integer"

        ];
    }
}
