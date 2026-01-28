<?php

namespace App\Services;

use Exception;
use App\Models\Bed;
use App\Models\Admission;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class BedService
{
    public function getDashboardBeds(): Collection
    {
        return Bed::query()
            ->with([
                'service',
                'activeAdmission.patient',    
                'futureAdmission.patient'    
            ])
            ->orderBy('service_id')            
            ->orderBy('label')
            ->get();
    }

    public function assignPatient(Bed $bed, array $data): Admission
    {
        return DB::transaction(function () use ($bed, $data) {

            $lockedBed = Bed::where('id', $bed->id)->lockForUpdate()->first();

            if ($lockedBed->status !== 'available') {
                throw new Exception("Bed {$lockedBed->label} is not available. Current status: {$lockedBed->status}");
            }

            $lockedBed->update([
                'status' => 'occupied',
            ]);

            return Admission::create([
                'bed_id' => $lockedBed->id,
                'patient_id' => $data['patient_id'],
                'status' => 'active',
                'admitted_at' => now(),
                'notes' => $data['notes'] ?? null,
            ]);
        });
    }

    /**
     * Discharge a patient and mark the bed as 'cleaning'.
     */
    public function dischargePatient(Bed $bed, array $data): Admission
    {
        return DB::transaction(function () use ($bed, $data) {

            $lockedBed = Bed::where('id', $bed->id)->lockForUpdate()->first();

            if ($lockedBed->status !== 'occupied') {
                throw new Exception("Cannot discharge: Bed {$lockedBed->label} is not currently occupied.");
            }

            $admission = $lockedBed->activeAdmission;

            if (!$admission) {
                throw new Exception("Data Error: Bed is marked 'occupied' but has no active admission record.");
            }

            $admission->update([
                'status' => 'discharged',
                'discharged_at' => now(),
                'notes' => $admission->notes . ($data['notes'] ? "\n[Discharge] " . $data['notes'] : ''),
            ]);

            $lockedBed->update([
                'status' => 'cleaning',
            ]);

            return $admission;
        });
    }


    /**
     * Manually update bed status
     */
    public function updateBedStatus(Bed $bed, array $data): Bed
    {
        return DB::transaction(function () use ($bed, $data) {

            $lockedBed = Bed::where('id', $bed->id)->lockForUpdate()->first();

            if ($lockedBed->status === 'occupied') {
                throw new Exception("Cannot change status: Bed {$lockedBed->label} is currently occupied by a patient. Discharge them first.");
            }

            $lockedBed->update([
                'status' => $data['status'],
            ]);

            return $lockedBed;
        });
    }


    /**
     * Create a new Bed.
     */
    public function createBed(array $data): Bed
    {
        return DB::transaction(function () use ($data) {
            
            return Bed::create([
                'service_id' => $data['service_id'],
                'label' => $data['label'],
                'status' => $data['status'] ?? 'available', 
            ]);
        });
    }


    /**
     * Update Bed details
     */
    public function updateBed(Bed $bed, array $data): Bed
    {
        return DB::transaction(function () use ($bed, $data) {
            $lockedBed = Bed::where('id', $bed->id)->lockForUpdate()->first();

            $lockedBed->update([
                'service_id' => $data['service_id'] ?? $lockedBed->service_id,
                'label' => $data['label'] ?? $lockedBed->label,
            ]);

            return $lockedBed;
        });
    }

    /**
     * Delete a Bed.
     * Rule: Cannot delete if occupied.
     */
    public function deleteBed(Bed $bed): void
    {
        DB::transaction(function () use ($bed) {
            $lockedBed = Bed::where('id', $bed->id)->lockForUpdate()->first();

            if ($lockedBed->status === 'occupied') {
                throw new Exception("Cannot delete bed {$lockedBed->label} because it is currently occupied. Discharge the patient first.");
            }

            $lockedBed->delete();
        });
    }
}