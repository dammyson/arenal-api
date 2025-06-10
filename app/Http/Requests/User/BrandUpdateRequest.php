<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class BrandUpdateRequest extends FormRequest
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
            'industry_code' => ['sometimes', 'string'],
            'sub_industry_code' => ['sometimes', 'string'],
            'slug' => ['sometimes', 'string'],
            'client_id' => ['sometimes', 'uuid', 'exists:Clients,id'],
        
        ];
    }
}
