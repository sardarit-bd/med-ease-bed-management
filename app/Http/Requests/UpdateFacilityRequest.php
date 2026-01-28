<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFacilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'sometimes', 
                'string', 
                'max:255', 
                Rule::unique('facilities')->ignore($this->facility)
            ],
            'type' => ['sometimes', 'string', 'in:Hospital,Clinic,Health Center'],
            'timezone' => ['sometimes', 'timezone'],
        ];
    }
}