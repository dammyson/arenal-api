<?php

namespace App\Http\Requests\SpinTheWheel;

use Illuminate\Foundation\Http\FormRequest;

class StoreSpinTheWheelButtonRequest extends FormRequest
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
            "spin_the_wheel_id" => "required|exists:spin_the_wheels,id",
            "button_color" => "sometimes|string", 
            "button_solid_style" => "sometimes|string", 
            "button_outline_style" => "sometimes|string", 
            "button_3d_styles" => "sometimes|string", 
            "button_custom_png" => "sometimes|string",
            "has_custom_png" => "sometimes|boolean"
        ];
    }
}
