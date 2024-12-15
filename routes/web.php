<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SetupWizardController;

use App\Http\Controllers\Backend\{
    AdminController,
    AuctionController,
    SettingController
};

Route::fallback(function () {
    $currentPath = request()->path();
    // Check if the path starts with "admin"
    if (str_starts_with($currentPath, 'admin')) {
        return redirect()->route('dashboard');
    }
    // Return a 404 response for non-admin unmatched routes
    abort(404);
});

Route::get('/setup-wizard', [SetupWizardController::class, 'show'])->name('setup.wizard');
Route::post('/setup-wizard', [SetupWizardController::class, 'store'])->name('setup.wizard.store');

Route::get('/', function () {
    //return view('welcome');
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit')->middleware('permission:profile-page-view');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update')->middleware('permission:profile-update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy')->middleware('permission:profile-delete');
    // Route::post('/create_token', [ProfileController::class, 'create_token'])->name('profile.create_token');
});

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->middleware(['verified'])->name('dashboard')->middleware('permission:dashboard-page-view');
    Route::get('/categories', [AdminController::class, 'categories'])->name('categories.index')->middleware('permission:category-page-view');
    Route::get("/players",[AdminController::class, 'players'])->name('players.index')->middleware('permission:player-page-view');
    Route::get("/teams",[AdminController::class, 'teams'])->name('teams.index')->middleware('permission:team-page-view');
    Route::get("/team/players/{id}",[AdminController::class, 'teamPlayers'])->name('team.players.index')->middleware('permission:team-page-view');
    Route::get("/leagues",[AdminController::class, 'league'])->name('leagues.index')->middleware('permission:league-page-view');
    Route::get("/sponsors",[AdminController::class, 'sponsors'])->name('sponsors.index')->middleware('permission:sponsor-page-view');
    Route::get("/clear-cache",[AdminController::class, 'clearCache'])->name('clear-cache')->middleware('permission:clear-cache-page-view');
    Route::get("/user-roles",[AdminController::class, 'userRoles'])->name('user-roles')->middleware('permission:user-role-page-view');
    Route::get("/users",[AdminController::class, 'users'])->name('users')->middleware('permission:user-page-view');
    Route::get("/permissions",[AdminController::class, 'permissions'])->name('permissions')->middleware('permission:user-permission-page-view');

    // admin.user-permissions.index
    
    // Handling both GET and POST requests on the same route
    Route::match(['get', 'post'], "/auction", [AuctionController::class, 'index'])->name('auction.index')->middleware('permission:auction-page-view');
    Route::get("/auction/update-league/{id}",[AuctionController::class, 'setLeagueId'])->name('set.league.id')->middleware('permission:bidding-page-view');
    Route::get('/bidding/start/{id}', [AuctionController::class, 'biddingStart'])->name('bidding.started')->middleware('permission:bidding-page-view');
    Route::get('/bidding', [AuctionController::class, 'biddingList'])->name('bidding.index')->middleware('permission:bidding-page-view');

    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index')->middleware('permission:setting-page-view');
    Route::put('/settings/update', [SettingController::class, 'update'])->name('settings.update')->middleware('permission:settings-update');    
});

require __DIR__.'/auth.php';
