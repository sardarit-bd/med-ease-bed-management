<?php

namespace App\Services;

use App\Models\Service;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Exception;

class DepartmentService
{
    /**
     * Get all services (optionally filtered by facility).
     */
    public function getAllServices(?string $facilityId = null): Collection
    {
        $query = Service::with('facility')->orderBy('name');

        if ($facilityId) {
            $query->where('facility_id', $facilityId);
        }

        return $query->get();
    }

    /**
     * Create a new Department.
     */
    public function createService(array $data): Service
    {
        return DB::transaction(function () use ($data) {
            return Service::create([
                'facility_id' => $data['facility_id'],
                'name' => $data['name'],
                'code' => $data['code'] ?? null,
            ]);
        });
    }

    /**
     * Update a Department.
     */
    public function updateService(Service $service, array $data): Service
    {
        return DB::transaction(function () use ($service, $data) {
            $service->update($data);
            return $service;
        });
    }

    /**
     * Delete a Department.
     * Rule: Cannot delete if it has Beds.
     */
    public function deleteService(Service $service): void
    {
        if ($service->beds()->exists()) {
            throw new Exception("Cannot delete '{$service->name}' because it contains Beds. Remove or move the beds first.");
        }

        $service->delete();
    }
}