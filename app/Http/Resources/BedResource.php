<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BedResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'bed_no' => $this->label,
            'service' => $this->service?->name,
            'status' => $this->status,
            'patient' => $this->getPatientName(),
            'latest_update' => $this->last_status_change_at?->format('H:i'),
            'admission_expected' => $this->formatAdmissionExpected(),
            'actions' => 'Manage', 
        ];
    }

    /**
     * Helper to determine what to show in the Patient column.
     */
    private function getPatientName(): string
    {
        if ($this->status === 'occupied' && $this->activeAdmission) {
            return $this->activeAdmission->patient->full_name;
        }

        return '-';
    }

    /**
     * Helper to format the "Admission Expected" column data.
     * Example output: "14:00 - MARTIN Marie"
     */
    private function formatAdmissionExpected(): string
    {
        if ($this->futureAdmission) {
            $time = $this->futureAdmission->expected_arrival_at->format('H:i');
            $name = $this->futureAdmission->patient->last_name . ' ' . $this->futureAdmission->patient->first_name;
            
            return "{$time} - {$name}";
        }

        return '-';
    }
}