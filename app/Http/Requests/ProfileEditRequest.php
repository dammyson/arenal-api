<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileEditRequest extends FormRequest
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
            'first_name'=> 'sometimes|required|string',
            'last_name'=> 'sometimes|required|string',
            'email'=> 'sometimes|required|email',
            'phone_number' => 'sometimes|required|string',
            'profile_image' => 'sometimes|string'
        ];
    }
}
