<?php

namespace App\Http\Requests\SpinTheWheel;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSpinTheWheelSegmentRequest extends FormRequest
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
            "label_text" => "sometimes|string", 
            "label_color" => "sometimes|string", 
            "background_color" => "sometimes|string", 
            "icon" => "sometimes|string", 
            "probability" => 'sometimes|numeric|min:0|max:1'
        ];
    }
}
