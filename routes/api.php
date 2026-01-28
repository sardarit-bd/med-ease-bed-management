<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BedController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// routs for bed
Route::apiResource('/beds', BedController::class);
Route::post('/beds/{id}/assign', [BedController::class, 'assignPatient']);
Route::post('/beds/{id}/discharge', [BedController::class, 'dischargePatient']);
Route::post('/beds/{id}/status', [BedController::class, 'updateStatus']);