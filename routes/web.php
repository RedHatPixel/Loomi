<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

// Home Route
Route::get('/', [Controller::class, 'home'])->name('home');

// Authentication Route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Verification Route
Route::get('/reset-password', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
Route::post('/forgot-password', [AuthController::class, 'showEmailLink'])->name('password.email');

// Guest Routes
Route::middleware('guest')->controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('show.login');
    Route::get('/register', 'showRegister')->name('show.register');
    Route::post('/login', 'login')->name('login');
    Route::post('/register', 'register')->name('register');
});

// Product Routes
Route::prefix('/products')->controller(ProductController::class)->group(function () {

    // Authenticated Routes
    Route::middleware('auth')->group(function () {
        Route::resource('/', ProductController::class)
            ->only(['store', 'update', 'destroy', 'create', 'edit']);
    });

    // Public Routes
    Route::get('/', 'index')->name('products.index');
    Route::get('/{product}', 'show')->name('products.show');
});

// User Authenticated Routes
Route::prefix('/user')->middleware('auth')->controller(UserController::class)->group(function () {

    // User Views
    Route::get('/', 'index')->name('user.index');
    Route::get('/edit', 'edit')->name('user.edit');
    Route::get('/orders', 'orders')->name('user.orders');
});

// Cart and Wishlist Routes
Route::middleware('auth')->group(function () {
    Route::resource('/cart', CartController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('/wishlist', WishlistController::class)->only(['index', 'store', 'destroy']);
});
