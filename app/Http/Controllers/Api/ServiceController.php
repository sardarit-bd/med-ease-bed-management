<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use App\Services\DepartmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    protected DepartmentService $departmentService;

    public function __construct(DepartmentService $departmentService)
    {
        $this->departmentService = $departmentService;
    }

    /**
     * List services. 
     */
    public function index(Request $request): JsonResponse
    {
        $services = $this->departmentService->getAllServices($request->query('facility_id'));

        return $this->successResponse(
            ServiceResource::collection($services),
            'Services retrieved successfully.'
        );
    }

    /**
     * Create a service.
     */
    public function store(StoreServiceRequest $request): JsonResponse
    {
        try {
            $service = $this->departmentService->createService($request->validated());

            return $this->successResponse(
                new ServiceResource($service),
                'Service (Department) created successfully.',
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Show a single service.
     */
    public function show(string $id): JsonResponse
    {
        $service = Service::with('facility')->findOrFail($id);
        
        return $this->successResponse(
            new ServiceResource($service),
            'Service details retrieved.'
        );
    }

    /**
     * Update a service.
     */
    public function update(UpdateServiceRequest $request, string $id): JsonResponse
    {
        try {
            $service = Service::findOrFail($id);
            $request->merge(['service' => $service]);

            $updatedService = $this->departmentService->updateService($service, $request->validated());

            return $this->successResponse(
                new ServiceResource($updatedService),
                'Service updated successfully.'
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Delete a service.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $service = Service::findOrFail($id);
            
            $this->departmentService->deleteService($service);

            return $this->successResponse(
                null,
                "Service '{$service->name}' deleted successfully."
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }
}