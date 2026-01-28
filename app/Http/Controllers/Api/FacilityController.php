<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFacilityRequest;
use App\Http\Requests\UpdateFacilityRequest;
use App\Http\Resources\FacilityResource;
use App\Models\Facility;
use App\Services\FacilityService;
use Illuminate\Http\JsonResponse;

class FacilityController extends Controller
{
    protected FacilityService $facilityService;

    public function __construct(FacilityService $facilityService)
    {
        $this->facilityService = $facilityService;
    }

    /**
     * List all facilities.
     */
    public function index(): JsonResponse
    {
        $facilities = $this->facilityService->getAllFacilities();
        
        return $this->successResponse(
            FacilityResource::collection($facilities),
            'Facilities retrieved successfully.'
        );
    }

    /**
     * Create a new facility.
     */
    public function store(StoreFacilityRequest $request): JsonResponse
    {
        try {
            $facility = $this->facilityService->createFacility($request->validated());

            return $this->successResponse(
                new FacilityResource($facility),
                'Facility created successfully.',
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Show a single facility.
     */
    public function show(string $id): JsonResponse
    {
        $facility = Facility::findOrFail($id);
        
        return $this->successResponse(
            new FacilityResource($facility),
            'Facility details retrieved.'
        );
    }

    /**
     * Update a facility.
     */
    public function update(UpdateFacilityRequest $request, string $id): JsonResponse
    {
        try {
            $facility = Facility::findOrFail($id);
            
            $request->merge(['facility' => $facility]);

            $updatedFacility = $this->facilityService->updateFacility($facility, $request->validated());

            return $this->successResponse(
                new FacilityResource($updatedFacility),
                'Facility updated successfully.'
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Delete a facility.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $facility = Facility::findOrFail($id);
            
            $this->facilityService->deleteFacility($facility);

            return $this->successResponse(
                null,
                "Facility '{$facility->name}' deleted successfully."
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }
}