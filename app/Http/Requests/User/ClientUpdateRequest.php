<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class ClientUpdateRequest extends FormRequest
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
            'name' => ['sometimes', 'string'],
            'image_url' => ['sometimes', 'string'],
            'company_id' => ['sometimes', 'uuid', 'exists:Companies,id'],
            'street_address' => ['sometimes', 'string'],
            'city' => ['sometimes', 'string'],
            'state' => ['sometimes', 'string'],
            'nationality' => ['sometimes', 'string'],
        ];
    }
}
