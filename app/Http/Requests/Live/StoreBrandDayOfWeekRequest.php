<?php

namespace App\Http\Requests\Live;

use Illuminate\Foundation\Http\FormRequest;

class StoreBrandDayOfWeekRequest extends FormRequest
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
            "live_id" => "required|string|exists:lives,id",
            "day_of_week_array" => "required|array",
            "day_of_week_array.*.day_of_week" => "required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday",
            "day_of_week_array.*.day_value" => "required|string"
        ];
    }
}
