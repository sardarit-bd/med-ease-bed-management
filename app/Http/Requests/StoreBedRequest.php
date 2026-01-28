<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBedRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
        
            'service_id' => ['required', 'uuid', 'exists:services,id'],

            'label' => [
                'required', 
                'string', 
                'max:20',
                Rule::unique('beds')->where(function ($query) {
                    return $query->where('service_id', $this->service_id);
                })
            ],

            'status' => ['nullable', 'string', Rule::in(['available', 'maintenance'])],
        ];
    }
}