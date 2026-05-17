<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\AuthController; // Don't forget to import the new controller!

// ------------------------------------
// PUBLIC ROUTES
// ------------------------------------
Route::get('/', [ShopController::class, 'index']);

// ------------------------------------
// AUTHENTICATION ROUTES
// ------------------------------------
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ------------------------------------
// SECURE ADMIN ROUTES (THE VAULT)
// ------------------------------------
// The 'auth' middleware acts as a bouncer. If a guest tries to access anything 
// inside this group, Laravel automatically kicks them to the 'login' route.
Route::middleware('auth')->group(function () {
    
    Route::get('/admin', [ShopController::class, 'adminIndex'])->name('admin.index');

    Route::post('/admin/shops', [ShopController::class, 'store'])->name('shops.store');

    // Phase 4: Edit and Update Routes
    Route::get('/admin/shops/{id}/edit', [ShopController::class, 'edit'])->name('shops.edit');
    Route::put('/admin/shops/{id}', [ShopController::class, 'update'])->name('shops.update');

    // Phase 3: Delete Route
    Route::delete('/admin/shops/{id}', [ShopController::class, 'destroy'])->name('shops.destroy');

});