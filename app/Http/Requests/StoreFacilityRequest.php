<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFacilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:facilities,name'],
            'type' => ['required', 'string', 'max:50', 'in:Hospital,Clinic,Health Center'],
            'timezone' => ['required', 'timezone'],
        ];
    }
}