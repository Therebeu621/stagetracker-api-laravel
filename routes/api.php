<?php

use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FollowupController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    // Applications
    Route::get('/applications/export.csv', [ApplicationController::class, 'exportCsv']);
    Route::apiResource('applications', ApplicationController::class);

    // Followups (nested under applications)
    Route::get('/applications/{application}/followups', [FollowupController::class, 'index']);
    Route::post('/applications/{application}/followups', [FollowupController::class, 'store']);

    // Standalone followup delete
    Route::delete('/followups/{followup}', [FollowupController::class, 'destroy']);
});
