<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\UserDashboardController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
});


// Admin
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', 'admin'])->prefix('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Products
    Route::get('/products', [ProductController::class, 'index'])->name('admin.products.index');
    Route::post('/products/{product}/published', [ProductController::class, 'changePublished'])->name('admin.products.published');
    Route::post('/products/store', [ProductController::class, 'store'])->name('admin.products.store');
    Route::put('/products/{product}/update', [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{product}/image/delete', [ProductController::class, 'deleteImage'])->name('admin.products.image.delete');
    Route::delete('/products/{product}/destroy', [ProductController::class, 'destroy'])->name('admin.products.destroy');
    Route::delete('/products/{product}/multiple/delete', [ProductController::class, 'deleteMultiple'])->name('admin.products.delete.multiple');
});