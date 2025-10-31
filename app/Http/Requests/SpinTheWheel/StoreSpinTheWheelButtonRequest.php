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
            "color" => "sometimes|string", 
            "is_solid" => "sometimes|boolean", 
            "border_radius" => "sometimes|string", 
            "button_3d_styles" => "sometimes|boolean", 
            "text" => "sometimes|string",
            "custom_button_url" => "sometimes|boolean"
        ];
    }
}
