<?php

namespace App\Http\Controllers\Api;

use App\Models\Bed;
use App\Services\BedService;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\BedResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBedRequest;
use App\Http\Requests\UpdateBedRequest;
use App\Http\Requests\AssignPatientRequest;
use App\Http\Requests\UpdateBedStatusRequest;
use App\Http\Requests\DischargePatientRequest;

class BedController extends Controller
{
    protected BedService $bedService;

    public function __construct(BedService $bedService)
    {
        $this->bedService = $bedService;
    }

    public function index(): JsonResponse
    {
        try {
            $beds = $this->bedService->getDashboardBeds();
            
            return $this->successResponse(
                BedResource::collection($beds), 
                'Bed management dashboard data retrieved successfully.'
            );

        } catch (\Exception $e) {
            return $this->errorResponse(
                'Unable to load dashboard data.', 
                500,
                app()->isLocal() ? $e->getMessage() : null
            );
        }
    }

    /**
     * Action: Admit a patient to a bed.
     */
    public function assignPatient(AssignPatientRequest $request, string $id): JsonResponse
    {
        try {
            $bed = Bed::findOrFail($id);
            $admission = $this->bedService->assignPatient($bed, $request->validated());

            return $this->successResponse(
                $admission,
                "Patient successfully assigned to bed {$bed->label}."
            );

        } catch (\Exception $e) {
            return $this->errorResponse(
                $e->getMessage(),
                422 
            );
        }
    }

    public function dischargePatient(DischargePatientRequest $request, string $id): JsonResponse
    {
        try {
            $bed = Bed::findOrFail($id);

            $this->bedService->dischargePatient($bed, $request->validated());

            return $this->successResponse(
                null, 
                "Patient successfully discharged. Bed {$bed->label} is now marked for Cleaning."
            );

        } catch (\Exception $e) {
            return $this->errorResponse(
                $e->getMessage(),
                422
            );
        }
    }

    /**
     * Action: Manually update bed status
     */
    public function updateStatus(UpdateBedStatusRequest $request, string $id): JsonResponse
    {
        try {
            $bed = Bed::findOrFail($id);

            $updatedBed = $this->bedService->updateBedStatus($bed, $request->validated());

            return $this->successResponse(
                null, 
                "Bed {$bed->label} status updated to '{$updatedBed->status}'."
            );

        } catch (\Exception $e) {
            return $this->errorResponse(
                $e->getMessage(),
                422
            );
        }
    }

    /**
     * Admin: Create a new bed.
     */
    public function store(StoreBedRequest $request): JsonResponse
    {
        try {
            $bed = $this->bedService->createBed($request->validated());

            return $this->successResponse(
                new BedResource($bed),
                "Bed {$bed->label} created successfully.",
                201
            );

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }


    public function update(UpdateBedRequest $request, string $id): JsonResponse
    {
        try {
            $bed = Bed::findOrFail($id);
            $request->merge(['bed' => $bed]);

            $updatedBed = $this->bedService->updateBed($bed, $request->validated());

            return $this->successResponse(
                new BedResource($updatedBed),
                "Bed details updated."
            );

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Admin: Delete a bed.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $bed = Bed::findOrFail($id);

            $this->bedService->deleteBed($bed);

            return $this->successResponse(
                null,
                "Bed {$bed->label} deleted successfully."
            );

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }
}