<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Http\Resources\PatientResource;
use App\Models\Patient;
use App\Services\PatientService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    protected PatientService $patientService;

    public function __construct(PatientService $patientService)
    {
        $this->patientService = $patientService;
    }

    /**
     * List patients.
     */
    public function index(Request $request): JsonResponse
    {
        $patients = $this->patientService->getAllPatients($request->query('search'));

        return $this->successResponse(
            PatientResource::collection($patients),
            'Patients retrieved successfully.'
        );
    }

    /**
     * Create a patient.
     */
    public function store(StorePatientRequest $request): JsonResponse
    {
        try {
            $patient = $this->patientService->createPatient($request->validated());

            return $this->successResponse(
                new PatientResource($patient),
                'Patient created successfully.',
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Show a single patient.
     */
    public function show(string $id): JsonResponse
    {
        $patient = Patient::findOrFail($id);
        
        return $this->successResponse(
            new PatientResource($patient),
            'Patient details retrieved.'
        );
    }

    /**
     * Update a patient.
     */
    public function update(UpdatePatientRequest $request, string $id): JsonResponse
    {
        try {
            $patient = Patient::findOrFail($id);
            $request->merge(['patient' => $patient]); // For validation context

            $updatedPatient = $this->patientService->updatePatient($patient, $request->validated());

            return $this->successResponse(
                new PatientResource($updatedPatient),
                'Patient details updated.'
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Delete a patient.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $patient = Patient::findOrFail($id);
            
            $this->patientService->deletePatient($patient);

            return $this->successResponse(
                null,
                "Patient '{$patient->full_name}' deleted successfully."
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }
}