<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'facility_id' => ['required', 'uuid', 'exists:facilities,id'],
            
            'name' => [
                'required', 
                'string', 
                'max:255',
                Rule::unique('services')->where(function ($query) {
                    return $query->where('facility_id', $this->facility_id);
                })
            ],
            
            'code' => ['nullable', 'string', 'max:10'],
        ];
    }
}