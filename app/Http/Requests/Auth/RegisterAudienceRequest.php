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
            'email_or_phone' => [
                'required',
                function ($attribute, $value, $fail) {
                    $isEmail = filter_var($value, FILTER_VALIDATE_EMAIL);
                    $isPhone = preg_match('/^\d{11}$/', $value); // Nigerian phone number format
    
                    if (!$isEmail && !$isPhone) {
                        $fail('The ' . $attribute . ' must be a valid email address or 11-digit phone number.');
                    }
    
                    if ($isEmail && \App\Models\Audience::where('email', $value)->exists()) {
                        $fail('The email has already been taken.');
                    } elseif ($isPhone && \App\Models\Audience::where('phone_number', $value)->exists()) {
                        $fail('The phone number has already been taken.');
                    }
                }
            ],
            'password' => [
                'required',
                'string',
                'min:6',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[_!@#$%]/',
                'confirmed',
            ]
        ];
    }
}
