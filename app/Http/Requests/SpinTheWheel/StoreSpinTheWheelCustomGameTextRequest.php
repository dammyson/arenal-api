<?php

namespace App\Http\Requests\SpinTheWheel;

use Illuminate\Foundation\Http\FormRequest;

class StoreSpinTheWheelCustomGameTextRequest extends FormRequest
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
            "game_title" => "sometimes|string", 
            "description" => "sometimes|string", 
            "style" => "sometimes|string"
        ];
    }
}
