<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBedRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'service_id' => ['sometimes', 'uuid', 'exists:services,id'],
            
            'label' => [
                'sometimes', 
                'string', 
                'max:20',
                Rule::unique('beds')->where(function ($query) {
                    return $query->where('service_id', $this->input('service_id', $this->bed->service_id));
                })->ignore($this->bed)
            ],
        ];
    }
}