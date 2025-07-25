<?php

namespace App\Http\Requests\Live;

use Illuminate\Foundation\Http\FormRequest;

class StoreLiveRequest extends FormRequest
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
            "brand_id" => "required|string|exists:brands,id",
            "duration" => "required|integer",
            "checkIn_amount" => "required|integer",
            "coins" => "required|integer"
        ];
    }
}
