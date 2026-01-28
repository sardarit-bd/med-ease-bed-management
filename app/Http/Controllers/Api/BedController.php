<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BedResource;
use App\Services\BedService;
use Illuminate\Http\JsonResponse;

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
}