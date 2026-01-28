<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            
            'mrn' => ['required', 'string', 'max:50', 'unique:patients,mrn'],
            
            'dob' => ['nullable', 'date', 'before:today'],
        ];
    }
}