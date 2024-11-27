<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Http\Controllers\Backend\Api\CategoryApiController;
use App\Http\Controllers\Backend\Api\PlayerApiController;
use App\Http\Controllers\Backend\Api\LeagueApiController;
use App\Http\Controllers\Backend\Api\SponsorApiController;
use App\Http\Controllers\Backend\Api\TeamApiController;
use App\Http\Controllers\Backend\Api\TransactionController;

Route::middleware(['auth:sanctum', 'throttle:100,1'])->prefix('backend')->group(function () {

    Route::get('/categories', [CategoryApiController::class, 'index']);
    Route::post('/categories/store', [CategoryApiController::class, 'store']);                
    Route::get('/categories/edit/{id}', [CategoryApiController::class, 'edit']);
    Route::post('/categories/{id}', [CategoryApiController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryApiController::class, 'destroy']);

    Route::get('/players', [PlayerApiController::class, 'index']);
    Route::post('/players/store', [PlayerApiController::class, 'store']);                
    Route::get('/players/edit/{id}', [PlayerApiController::class, 'edit']);
    Route::post('/players/{id}', [PlayerApiController::class, 'update']);
    Route::delete('/players/{id}', [PlayerApiController::class, 'destroy']);    
    Route::get('/players/view/{id}', [PlayerApiController::class, 'view']);

    Route::get('/teams', [TeamApiController::class, 'index']);
    Route::post('/teams/store', [TeamApiController::class, 'store']);                
    Route::get('/teams/edit/{id}', [TeamApiController::class, 'edit']);
    Route::post('/teams/{id}', [TeamApiController::class, 'update']);
    Route::delete('/teams/{id}', [TeamApiController::class, 'destroy']);

    Route::post('/transactions/store', [TransactionController::class, 'store']);

    Route::get('/leagues', [LeagueApiController::class, 'index']);
    Route::post('/leagues/store', [LeagueApiController::class, 'store']);                
    Route::get('/leagues/edit/{id}', [LeagueApiController::class, 'edit']);
    Route::post('/leagues/{id}', [LeagueApiController::class, 'update']);
    Route::delete('/leagues/{id}', [LeagueApiController::class, 'destroy']);

    Route::get('/sponsors', [SponsorApiController::class, 'index']);
    Route::post('/sponsors/store', [SponsorApiController::class, 'store']);                
    Route::get('/sponsors/edit/{id}', [SponsorApiController::class, 'edit']);
    Route::post('/sponsors/{id}', [SponsorApiController::class, 'update']);
    Route::delete('/sponsors/{id}', [SponsorApiController::class, 'destroy']);
});