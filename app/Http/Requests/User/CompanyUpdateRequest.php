<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class CompanyUpdateRequest extends FormRequest
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
            'company_name' => ['sometimes', 'string'],
            'company_address' => ['sometimes', 'string'],
            'company_logo' => ['sometimes', 'string'],
            'company_rc' => ['sometimes', 'string'],
            'company_email' => ['sometimes', 'email'],
            'company_phone_number' => ['sometimes', 'string'],
            'company_website' => ['sometimes', 'string'],
            'company_city' => ['sometimes', 'string'],
            'company_state' => ['sometimes', 'string'],
            'company_country' => ['sometimes', 'string'], 
        ];
    }
}
