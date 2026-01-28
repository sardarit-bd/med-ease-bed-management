<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBedStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => [
                'required', 
                'string', 
                Rule::in(['available', 'maintenance', 'cleaning'])
            ],
            
            'notes' => ['nullable', 'string', 'max:255'], 
        ];
    }
}