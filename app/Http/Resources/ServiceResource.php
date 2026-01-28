<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,      
            'code' => $this->code,   

            'facility' => $this->facility?->name, 
            'facility_id' => $this->facility_id,
            
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}