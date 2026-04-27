<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;

Route::get('/', [ShopController::class, 'index']);
Route::view('/admin', 'admin');
Route::post('/admin/shops', [App\Http\Controllers\ShopController::class, 'store'])->name('shops.store');