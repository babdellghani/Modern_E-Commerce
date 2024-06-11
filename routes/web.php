<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\User\UserDashboardController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// User & Guest
Route::prefix('cart')->controller(CartController::class)->group(function () {
    Route::get('/', 'index')->name('cart.index');
    Route::post('/add/{product}', 'store')->name('cart.store');
    Route::put('/update/{product}/{quantity}', 'update')->name('cart.update');
    Route::delete('/remove/{product}', 'destroy')->name('cart.destroy');
});

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

    // Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
    Route::post('/categories/store', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::put('/categories/{category}/update', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/categories/{category}/destroy', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');
    Route::delete('/categories/{category}/multiple/delete', [CategoryController::class, 'deleteMultiple'])->name('admin.categories.delete.multiple');

    // Brand
    Route::get('/brands', [BrandController::class, 'index'])->name('admin.brands.index');
    Route::post('/brands/store', [BrandController::class, 'store'])->name('admin.brands.store');
    Route::put('/brands/{brand}/update', [BrandController::class, 'update'])->name('admin.brands.update');
    Route::delete('/brands/{brand}/destroy', [BrandController::class, 'destroy'])->name('admin.brands.destroy');
    Route::delete('/brands/{brand}/multiple/delete', [BrandController::class, 'deleteMultiple'])->name('admin.brands.delete.multiple');
});