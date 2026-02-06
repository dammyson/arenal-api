<?php

namespace App\Http\Requests\Campaign;

use Illuminate\Foundation\Http\FormRequest;

class StoreCampaignRewardRequest extends FormRequest
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
            'type' => 'required|in:cash,airtime,item',
            'reward_name' => 'required|string',
            'points_required' => 'required|integer|min:1',
            'stock_total' => 'nullable|integer|min:1',
            'is_active' => 'boolean',

            // Cash
            'amount_per_redemption' => 'required_if:type,cash|numeric|min:1',

            // Airtime
            'airtime_value' => 'required_if:type,airtime|numeric|min:1',
            'network' => 'required_if:type,airtime|string',

            // Item
            'item_name' => 'required_if:type,item|string',
            'item_sku' => 'required_if:type,item|string',
            'item_description' => 'nullable|string',
        ];
    }
}
