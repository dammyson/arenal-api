<?php

namespace App\Http\Requests\Arena;

use Illuminate\Foundation\Http\FormRequest;

class StoreArenaSpinTheWheelAudiencePrizeRequest extends FormRequest
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
            'prizes' => 'required|array',
            'prizes.*.prize_id' => 'required|exists:prizes,id',
            'prizes.*.brand_id' => 'required|exists:brands,id',
        ];
    }
}
