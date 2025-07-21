<?php

namespace App\Http\Requests\Live;

use Illuminate\Foundation\Http\FormRequest;

class StoreJoinLiveRequest extends FormRequest
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
            "live_id" => "required|string|exists:lives,id",
            "coined_earned" => "required|integer"
        ];
    }
}
