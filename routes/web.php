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

/*
Route::get('/admin/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/create_token', [ProfileController::class, 'create_token'])->name('profile.create_token');    
});

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->middleware(['verified'])->name('dashboard');
    Route::get('/categories', [AdminController::class, 'categories'])->name('categories.index');
    Route::get("/players",[AdminController::class, 'players'])->name('players.index');
    Route::get("/teams",[AdminController::class, 'teams'])->name('teams.index');
    Route::get("/team/players/{id}",[AdminController::class, 'teamPlayers'])->name('team.players.index');
    Route::get("/leagues",[AdminController::class, 'league'])->name('leagues.index');
    Route::get("/sponsors",[AdminController::class, 'sponsors'])->name('sponsors.index');
    Route::get("/clear-cache",[AdminController::class, 'clearCache'])->name('clear-cache');    
    Route::get("/user-roles",[AdminController::class, 'userRoles'])->name('user-roles');
    Route::get("/users",[AdminController::class, 'users'])->name('users');
    
    // Handling both GET and POST requests on the same route
    Route::match(['get', 'post'], "/auction", [AuctionController::class, 'index'])->name('auction.index');
    Route::get("/auction/update-league/{id}",[AuctionController::class, 'setLeagueId'])->name('set.league.id');
    Route::get('/bidding/start/{id}', [AuctionController::class, 'biddingStart'])->name('bidding.started');    
    Route::get('/bidding', [AuctionController::class, 'biddingList'])->name('bidding.index');    

    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings/update', [SettingController::class, 'update'])->name('settings.update');


    
    

    
});

require __DIR__.'/auth.php';
