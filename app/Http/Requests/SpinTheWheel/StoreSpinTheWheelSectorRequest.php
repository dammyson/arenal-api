<?php

namespace App\Http\Requests\SpinTheWheel;

use Illuminate\Foundation\Http\FormRequest;

class StoreSpinTheWheelSectorRequest extends FormRequest
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
           'spin_the_wheel_id' => 'sometimes|exists:spin_the_wheels,id',
           "sectors" => "array|required",
           "sectors.*.text" => 'sometimes|string',
           "sectors.*.color" => 'sometimes|string',           
           "sectors.*.value" => 'sometimes|string',
           "sectors.*.image_url" => 'sometimes|string',
          
        ];
    }
}
