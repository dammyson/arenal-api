<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BrandStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'image_url' => ['nullable', 'string'],
            'industry_code' => ['nullable', 'string'],
            'sub_industry_code' => ['nullable', 'string'],
            'slug' => ['nullable', 'string'],
            'created_by' => ['required', 'uuid', 'exists:Users,id'],
            'client_id' => ['required', 'uuid', 'exists:Clients,id'],
        ];
    }
}
