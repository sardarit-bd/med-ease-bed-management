<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateServiceRequest extends FormRequest
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
                Rule::unique('services')->where(function ($query) {
                    $facilityId = $this->input('facility_id', $this->service->facility_id);
                    return $query->where('facility_id', $facilityId);
                })->ignore($this->service)
            ],
            
            'code' => ['nullable', 'string', 'max:10'],
        ];
    }
}