<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EquipmentController;
use App\Http\Controllers\Api\LoanController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    
    // Equipment
    Route::get('/equipment', [EquipmentController::class, 'index']);
    Route::get('/equipment/available', [EquipmentController::class, 'available']);
    Route::get('/equipment/{id}', [EquipmentController::class, 'show']);
    
    // Loans
    Route::get('/loans', [LoanController::class, 'index']);
    Route::get('/my-loans', [LoanController::class, 'myLoans']);
    Route::post('/loans', [LoanController::class, 'store']);
    Route::post('/loans/{id}/checkin', [LoanController::class, 'checkin']);
    
    // Admin only
    Route::middleware('admin')->group(function () {
        Route::post('/equipment', [EquipmentController::class, 'store']);
        Route::put('/equipment/{id}', [EquipmentController::class, 'update']);
        Route::delete('/equipment/{id}', [EquipmentController::class, 'destroy']);
    });
});