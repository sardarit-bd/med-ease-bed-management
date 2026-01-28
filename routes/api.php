<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BedController;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\FacilityController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// routs for bed
Route::apiResource('/beds', BedController::class);
Route::post('/beds/{id}/assign', [BedController::class, 'assignPatient']);
Route::post('/beds/{id}/discharge', [BedController::class, 'dischargePatient']);
Route::post('/beds/{id}/status', [BedController::class, 'updateStatus']);


// facility (hospital, clinic, etc)
Route::apiResource('facilities', FacilityController::class);


// services
Route::apiResource('services', ServiceController::class);


// patients
Route::apiResource('patients', PatientController::class);