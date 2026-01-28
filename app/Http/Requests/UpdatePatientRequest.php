<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['sometimes', 'string', 'max:255'],
            'last_name' => ['sometimes', 'string', 'max:255'],
            
            'mrn' => [
                'sometimes', 
                'string', 
                'max:50', 
                Rule::unique('patients')->ignore($this->patient)
            ],
            
            'dob' => ['nullable', 'date', 'before:today'],
        ];
    }
}