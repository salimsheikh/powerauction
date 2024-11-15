<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Http\Controllers\Backend\Api\CategoryApiController;

Route::middleware('auth:sanctum')->group(function () {
    // Add your API routes here.
});

Route::middleware('auth:sanctum')->prefix('backend')->group(function () {
    Route::get('/categories', [CategoryApiController::class, 'index']);
    Route::get('/categories/search', [CategoryApiController::class, 'search']);
    Route::get('/categories/refresh', [CategoryApiController::class, 'refresh']);
    Route::put('/categories/{id}', [CategoryApiController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryApiController::class, 'destroy']);
});

