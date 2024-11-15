<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

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
