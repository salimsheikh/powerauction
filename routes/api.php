<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Http\Controllers\Backend\Api\CategoryApiController;

Route::middleware('auth:sanctum')->group(function () {
    // Add your API routes here.
});

Route::middleware(['auth:sanctum', 'throttle:100,6'])->prefix('backend')->group(function () {
    Route::get('/categories', [CategoryApiController::class, 'index']);
    Route::post('/categories/store', [CategoryApiController::class, 'store']);                
    Route::get('/categories/edit/{id}', [CategoryApiController::class, 'edit']);
    Route::put('/categories/{id}', [CategoryApiController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryApiController::class, 'destroy']);    
});