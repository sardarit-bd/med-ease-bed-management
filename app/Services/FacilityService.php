<?php

namespace App\Services;

use App\Models\Facility;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Exception;

class FacilityService
{
    /**
     * Get all facilities.
     */
    public function getAllFacilities(): Collection
    {
        return Facility::orderBy('name')->get();
    }

    /**
     * Create a new Facility.
     */
    public function createFacility(array $data): Facility
    {
        return DB::transaction(function () use ($data) {
            return Facility::create([
                'name' => $data['name'],
                'type' => $data['type'],
                'timezone' => $data['timezone'],
            ]);
        });
    }

    /**
     * Update a Facility.
     */
    public function updateFacility(Facility $facility, array $data): Facility
    {
        return DB::transaction(function () use ($facility, $data) {
            $facility->update($data);
            return $facility;
        });
    }

    /**
     * Delete a Facility.
     * Rule: Cannot delete if it has linked Services (Departments).
     */
    public function deleteFacility(Facility $facility): void
    {
        if ($facility->services()->exists()) {
            throw new Exception("Cannot delete '{$facility->name}' because it has active Services (Departments). Delete them first.");
        }

        $facility->delete();
    }
}