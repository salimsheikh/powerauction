<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\Backend\Api\{
    CategoryApiController,
    PlayerApiController,
    LeagueApiController,
    SponsorApiController,
    TransactionController,
    BiddingApiController,
    TeamApiController,
    TeamPlayerApiController,
    UserRoleApiController,
    UserApiController,
    UserPermissionController
};

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum', 'throttle:10000,1'])->prefix('backend')->group(function () {
    
    Route::get('/categories', [CategoryApiController::class, 'index'])->name('category.api.index')->middleware('permission:category-list');
    Route::post('/categories/store', [CategoryApiController::class, 'store'])->name('category.api.store')->middleware('permission:category-create');
    Route::get('/categories/edit/{id}', [CategoryApiController::class, 'edit'])->name('category.api.edit')->middleware('permission:category-edit');
    Route::post('/categories/{id}', [CategoryApiController::class, 'update'])->name('category.api.update')->middleware('permission:category-update');
    Route::delete('/categories/{id}', [CategoryApiController::class, 'destroy'])->name('category.api.destroy')->middleware('permission:category-delete');

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

    Route::get('/transactions/{team_id}', [TransactionController::class, 'index']);
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

    Route::get('/team/players', [TeamPlayerApiController::class, 'index']);
    Route::post('/team/players/store', [TeamPlayerApiController::class, 'store']);
    Route::delete('/team/players/{id}', [TeamPlayerApiController::class, 'destroy']);

    Route::get('/bidding', [BiddingApiController::class, 'index'])->name('admin.bidding.index');
    Route::post('/bidding/start-bidding', [BiddingApiController::class, 'startBidding'])->name('admin.bidding.start-bidding');
    Route::post('/bidding/bid', [BiddingApiController::class, 'bid'])->name('admin.bidding.bid');
    Route::get('/bidding/bid-win/{id}', [BiddingApiController::class, 'bidWin'])->name('admin.bidding.bid-win');


    Route::get('/user-roles', [UserRoleApiController::class, 'index'])->name('admin.user-roles.index');
    Route::post('/user-roles/store', [UserRoleApiController::class, 'store'])->name('admin.user-roles.store');
    Route::get('/user-roles/edit/{id}', [UserRoleApiController::class, 'edit'])->name('admin.user-roles.edit');
    Route::post('/user-roles/{id}', [UserRoleApiController::class, 'update'])->name('admin.user-roles.update');
    Route::delete('/user-roles/{id}', [UserRoleApiController::class, 'destroy'])->name('admin.user-roles.destroy');

    Route::get('/users', [UserApiController::class, 'index'])->name('admin.users.index');
    Route::post('/users/store', [UserApiController::class, 'store'])->name('admin.users.store');
    Route::get('/users/edit/{id}', [UserApiController::class, 'edit'])->name('admin.users.edit');
    Route::post('/users/{id}', [UserApiController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{id}', [UserApiController::class, 'destroy'])->name('admin.users.destroy');

    Route::get('/permissions', [UserPermissionController::class, 'index'])->name('admin.user-permissions.index');
    Route::post('/permissions/store', [UserPermissionController::class, 'store'])->name('admin.user-permissions.store');
    Route::get('/permissions/edit/{id}', [UserPermissionController::class, 'edit'])->name('admin.user-permissions.edit');
    Route::post('/permissions/{id}', [UserPermissionController::class, 'update'])->name('admin.user-permissions.update');
    Route::delete('/permissions/{id}', [UserPermissionController::class, 'destroy'])->name('admin.user-permissions.destroy');
});