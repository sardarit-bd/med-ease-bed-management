<?php

namespace App\Services;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Exception;

class PatientService
{
    /**
     * Get all patients (Searchable).
     */
    public function getAllPatients(?string $search = null): Collection
    {
        $query = Patient::query()->orderBy('last_name');

        if ($search) {
            $query->where('last_name', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('mrn', 'like', "%{$search}%");
        }

        return $query->get();
    }

    /**
     * Create a new Patient.
     */
    public function createPatient(array $data): Patient
    {
        return DB::transaction(function () use ($data) {
            return Patient::create($data);
        });
    }

    /**
     * Update a Patient.
     */
    public function updatePatient(Patient $patient, array $data): Patient
    {
        return DB::transaction(function () use ($patient, $data) {
            $patient->update($data);
            return $patient;
        });
    }

    /**
     * Delete a Patient.
     * Rule: Cannot delete if they have admissions (History).
     */
    public function deletePatient(Patient $patient): void
    {
        if ($patient->admissions()->exists()) {
            throw new Exception("Cannot delete patient '{$patient->full_name}' because they have medical records (Admissions).");
        }

        $patient->delete();
    }
}