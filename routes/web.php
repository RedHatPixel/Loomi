<?php

use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Seller\DashboardController;
use App\Http\Controllers\Seller\OrderController as SellerOrderController;
use App\Http\Controllers\Seller\ProductController as SellerProductController;
use App\Http\Controllers\Api\UploadController;
use App\Http\Controllers\Seller\StoreController as SellerStoreController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Admin
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard',             [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Users
    Route::get('/users',                 [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}',          [\App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');
    Route::patch('/users/{user}/role',   [\App\Http\Controllers\Admin\UserController::class, 'updateRole'])->name('users.role');
    Route::delete('/users/{user}',       [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');

    // Stores
    Route::get('/stores',                [\App\Http\Controllers\Admin\StoreController::class, 'index'])->name('stores.index');
    Route::get('/stores/{store}',        [\App\Http\Controllers\Admin\StoreController::class, 'show'])->name('stores.show');
    Route::post('/stores/{store}/toggle',[\App\Http\Controllers\Admin\StoreController::class, 'toggle'])->name('stores.toggle');
    Route::delete('/stores/{store}',     [\App\Http\Controllers\Admin\StoreController::class, 'destroy'])->name('stores.destroy');

    // Products
    Route::get('/products',              [\App\Http\Controllers\Admin\ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}',    [\App\Http\Controllers\Admin\ProductController::class, 'show'])->name('products.show');
    Route::post('/products/{product}/toggle', [\App\Http\Controllers\Admin\ProductController::class, 'toggle'])->name('products.toggle');
    Route::delete('/products/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('products.destroy');

    // Orders
    Route::get('/orders',                [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}',        [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.status');
    Route::delete('/orders/{order}',     [\App\Http\Controllers\Admin\OrderController::class, 'destroy'])->name('orders.destroy');

    // Categories
    Route::get('/categories',            [\App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories',           [\App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('categories.store');
    Route::patch('/categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('categories.destroy');
});

// Public
Route::get('/', [HomeController::class, 'index'])->name('home');

// Sellers
Route::get('/seller', fn () => Inertia::render('Seller/Landing'))->name('seller.landing');
Route::get('/seller/create', fn () => Inertia::render('Seller/Create'))->middleware(['auth', 'verified'])->name('seller.create');
Route::post('/seller', [StoreController::class, 'store'])->middleware(['auth', 'verified'])->name('seller.store');

// Seller dashboard
Route::middleware(['auth', 'verified', 'ensure.stores'])->prefix('seller')->name('seller.')->group(function () {
    Route::get('/dashboard',       [DashboardController::class, 'index'])->name('dashboard');

    // Products
    Route::get('/products',             [SellerProductController::class, 'index'])->name('products.index');
    Route::get('/products/create',      [SellerProductController::class, 'create'])->name('products.create');
    Route::post('/products',            [SellerProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit',  [SellerProductController::class, 'edit'])->name('products.edit');
    Route::patch('/products/{product}',     [SellerProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}',    [SellerProductController::class, 'destroy'])->name('products.destroy');
    Route::post('/products/{product}/toggle', [SellerProductController::class, 'toggle'])->name('products.toggle');

    // Orders
    Route::get('/orders',                          [SellerOrderController::class, 'index'])->name('orders.index');
    Route::patch('/orders/{order}/status',         [SellerOrderController::class, 'updateStatus'])->name('orders.status');

    // Settings
    Route::get('/settings',          [SellerStoreController::class, 'edit'])->name('settings');
    Route::patch('/stores/{store}',  [SellerStoreController::class, 'update'])->name('stores.update');
    Route::delete('/stores/{store}', [SellerStoreController::class, 'destroy'])->name('stores.destroy');
});

// Stores
Route::get('/stores', [App\Http\Controllers\StoreController::class, 'index'])->name('stores.index');
Route::get('/stores/{store:slug}', [App\Http\Controllers\StoreController::class, 'show'])->name('stores.show');

// Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

// File uploads
Route::middleware(['auth'])->prefix('api')->name('api.')->group(function () {
    Route::post('/uploads/image', [UploadController::class, 'image'])->name('uploads.image');

    // Notifications
    Route::patch('/notifications/{notification}/read', [\App\Http\Controllers\Api\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::patch('/notifications/read-all',            [\App\Http\Controllers\Api\NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
});

// Authenticated
Route::middleware(['auth', 'verified'])->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Cart
    Route::get('/cart',               [CartController::class, 'index'])->name('cart');
    Route::post('/cart',              [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{cartItem}',  [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');

    // Notifications
    Route::get('/notifications',             [\App\Http\Controllers\Customer\NotificationController::class, 'index'])->name('notifications.index');

    // Orders
    Route::get('/orders',                  [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}',          [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders',                 [OrderController::class, 'store'])->name('orders.store');
    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::patch('/orders/{order}/cancel-store/{storeId}', [OrderController::class, 'cancelStore'])->name('orders.cancelStore');
});

require __DIR__.'/auth.php';
