<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

// Home Route
Route::get('/', [UserController::class, 'home'])->name('home');

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

// Product Public Routes
Route::prefix('/products')->controller(ProductController::class)->group(function () {
    Route::get('/', 'index')->name('products.index');
    Route::get('/random', 'random')->name('products.random');
    Route::get('/{product}', 'show')->name('products.show');
});

// User Authenticated Routes
Route::prefix('/user')->middleware('auth')->controller(UserController::class)->group(function () {
    // User Views
    Route::get('/', 'index')->name('user.index');
    Route::get('/edit', 'edit')->name('user.edit');

    // Profile Routes
    Route::post('/edit/update', [ProfileController::class, 'storeOrUpdate'])->name('profile.update');
});

// Resource Authenticated Routes
Route::middleware('auth')->group(function () {

    // Wishlist Routes
    Route::delete('/wishlist/clear', [WishlistController::class, 'clear'])->name('wishlist.clear');
    Route::resource('/wishlist', WishlistController::class)->only(['index', 'store', 'destroy']);

    // Cart Routes
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::resource('/cart', CartController::class)->only(['index', 'store', 'update', 'destroy']);

    // Order Routes
    Route::delete('/orders/cancel/{order}', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::put('/orders/restore/{order}', [OrderController::class, 'restore'])->name('orders.restore');
    Route::resource('/orders', OrderController::class)->only(['index', 'show', 'store', 'destroy']);

    // Sale Routes
    Route::post('/sales/{order}', [SaleController::class, 'store'])->name('sales.store');

    // Checkout Routes
    Route::resource('/checkout', CheckoutController::class)->only(['index', 'store']);

    // Address Routes
    Route::resource('/address', AddressController::class)->only(['create', 'store', 'destroy']);

    // Rating Routes
    Route::post('/rating/{product}', [RatingController::class, 'store'])->name('rate');
});

// Admin Routes
Route::prefix('admin')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        // Product Sales
        Route::get('/sales', [AdminController::class, 'sales'])->name('admin.sales');

        // Users Control Routes
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
        Route::post('/users/ban/{user}', [UserController::class, 'ban'])->name('admin.user.ban');
        Route::delete('/users/delete/{user}', [UserController::class, 'destroy'])->name('admin.user.delete');

        // Products Control Routes
        Route::get('/products', [AdminController::class, 'products'])->name('admin.products');
        Route::resource('/products', ProductController::class)->only(['store', 'update', 'destroy', 'create', 'edit']);

        // Orders Routes
        Route::get('/orders', [AdminController::class, 'orders'])->name('admin.orders');
        Route::get('/orders/{order}', [AdminController::class, 'order'])->name('admin.order');
        Route::put('/orders/accept/{order}', [OrderController::class, 'accept'])->name('admin.orders.accept');
        Route::put('/orders/deny/{order}', [OrderController::class, 'deny'])->name('admin.orders.deny');
        Route::put('/orders/update/{order}', [OrderController::class, 'update'])->name('admin.orders.update');

        Route::resource('/category', CategoryController::class)->only(['store', 'destroy']);
    });
