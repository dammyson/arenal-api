<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterAudienceRequest extends FormRequest
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
             // user info 
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone_number' => 'required|numeric|digits:11|unique:users',
            'email' => 'required|email|unique:users',
            'password' => [
                'required',
                'string',
                'min:6', // At least six characters
                'regex:/.*_.*$/',  // Ensures at least one underscore
                'confirmed', // Must match password confirmation
            ],

        ];
    }
}
