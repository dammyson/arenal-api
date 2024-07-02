<?php

namespace App\Http\Requests\Campaign;

use Illuminate\Foundation\Http\FormRequest;

class StoreCampaignRequest extends FormRequest
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
            'type' => 'required|string|min:3',
            'title' => 'required|string',
            'brand_id' => ['required', 'uuid', 'exists:brands,id'],
            'client_id' => ['required', 'uuid', 'exists:clients,id'],
            'company_id' => ['required', 'uuid', 'exists:companies,id'],
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'daily_start' => 'sometimes|required|date_format:H:i:s',
            'daily_stop' => 'sometimes|required|date_format:H:i:s',
        ];
    }
}
