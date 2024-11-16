<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SetupWizardController;
use App\Http\Controllers\Backend\CategoryController;

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

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');  // Show categories list
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create'); // Show form to create category
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store'); // Store new category
    Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit'); // Show edit form
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update'); // Update category
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy'); // Delete category
    
     // Display all categories
    //  Route::get('/admin/categories', [CategoryController::class, 'index'])->name('backend.categories.index');

    // Route::post('/backend/category/store', [CategoryController::class, 'store'])->name('backend.category.store');
});

require __DIR__.'/auth.php';
