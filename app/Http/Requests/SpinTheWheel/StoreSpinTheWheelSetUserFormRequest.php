<?php

namespace App\Http\Requests\SpinTheWheel;

use Illuminate\Foundation\Http\FormRequest;

class StoreSpinTheWheelSetUserFormRequest extends FormRequest
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
            "is_user_name" => "sometimes|boolean", 
            "is_user_email" => "sometimes|boolean", 
            "is_phone_number" => "sometimes|boolean", 
            "is_marked_required" => "sometimes|boolean",
            'title'  => "sometimes|string",
            'description'  => "sometimes|string",
            'text_style'  => "sometimes|string",
            'show_before_spin'  => "sometimes|boolean",
        ];
    }
}
