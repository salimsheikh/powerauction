<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SetupWizardController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Backend\PlayerController;

Route::get('/', function () {
    //return view('welcome');
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::get('/setup-wizard', [SetupWizardController::class, 'show'])->name('setup.wizard');
Route::post('/setup-wizard', [SetupWizardController::class, 'store'])->name('setup.wizard.store');

Route::get('/admin/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/create_token', [ProfileController::class, 'create_token'])->name('profile.create_token');    
});

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get("/players",[PlayerController::class, 'index'])->name('players.index');

    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings/update', [SettingController::class, 'update'])->name('settings.update');
});

require __DIR__.'/auth.php';
