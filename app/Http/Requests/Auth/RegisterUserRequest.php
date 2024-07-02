<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
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
            'password' => 'required|string|min:6|confirmed',

            // company info 
            'company_name' => ['required', 'string'],
            'company_address' => ['nullable', 'string'],
            'company_logo' => ['nullable', 'string'],
            'company_rc' => ['nullable', 'string'],
            'company_email' => ['nullable', 'email'],
            'company_phone_number' => ['nullable', 'string'],
            'company_website' => ['nullable', 'string'],
            'company_city' => ['nullable', 'string'],
            'company_state' => ['nullable', 'string'],
            'company_country' => ['nullable', 'string'],  
        ];
    }
}
