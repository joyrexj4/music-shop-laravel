<?php

use App\Http\Controllers\AuthController;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AdminClientController;
use App\Http\Middleware\CheckClientBlocked;
use App\Http\Controllers\AdminEmployeeController;
use Illuminate\Support\Facades\Gate;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
Route::get('/products/category/{id}', [ProductController::class, 'byCategory'])->name('products.category');

Route::get('/search', [SearchController::class, 'search'])->name('search');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
Route::get('/cart', [CartController::class, 'index'])->name('cart')->middleware('auth:client');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add')->middleware('auth:client');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update')->middleware('auth:client');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove')->middleware('auth:client');
Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout')->middleware('auth:client');
Route::get('/cart/success', [CartController::class, 'success'])->name('cart.success')->middleware('auth:client');

Route::get('/privacy', function() {
    $categories = ProductCategory::orderBy('Name')->get();
    return view('privacy', compact('categories'));
})->name('privacy');

Route::get('/admin/dashboard', function() {
    return view('admin.dashboard');
})->middleware('auth:employee')->name('admin.dashboard');

Route::prefix('admin')->middleware('auth:employee')->group(function () {
    
    // Админ (1), Сотрудник (2)
    Route::middleware('can:manage-products')->group(function () {
        Route::get('/products', [AdminProductController::class, 'index'])->name('admin.products.index');
        Route::get('/products/trashed', [AdminProductController::class, 'trashed'])->name('admin.products.trashed');
        Route::get('/products/create', [AdminProductController::class, 'create'])->name('admin.products.create');
        Route::post('/products', [AdminProductController::class, 'store'])->name('admin.products.store');
        Route::get('/products/{id}/edit', [AdminProductController::class, 'edit'])->name('admin.products.edit');
        Route::put('/products/{id}', [AdminProductController::class, 'update'])->name('admin.products.update');
        Route::delete('/products/{id}', [AdminProductController::class, 'destroy'])->name('admin.products.destroy');
        Route::delete('/products/{id}/force', [AdminProductController::class, 'forceDelete'])->name('admin.products.force-delete');
        Route::put('/products/{id}/restore', [AdminProductController::class, 'restore'])->name('admin.products.restore');
        Route::get('/products/search', [AdminProductController::class, 'search'])->name('admin.products.search');
    });

    // Админ (1), Сотрудник (2), Доставщик (3)
    Route::middleware('can:manage-orders')->group(function () {
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
        Route::get('/orders/{id}/edit', [AdminOrderController::class, 'edit'])->name('admin.orders.edit');
        Route::put('/orders/{id}', [AdminOrderController::class, 'update'])->name('admin.orders.update');
        Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
        Route::get('/orders/search', [AdminOrderController::class, 'search'])->name('admin.orders.search');
    });

    // Админ (1), Сотрудник (2)
    Route::middleware('can:manage-clients')->group(function () {
        Route::get('/clients/search', [AdminClientController::class, 'search'])->name('admin.clients.search');
        Route::get('/clients', [AdminClientController::class, 'index'])->name('admin.clients.index');
        Route::get('/clients/active', [AdminClientController::class, 'active'])->name('admin.clients.active');
        Route::get('/clients/blocked', [AdminClientController::class, 'blocked'])->name('admin.clients.blocked');
        Route::get('/clients/{id}', [AdminClientController::class, 'show'])->name('admin.clients.show');
        Route::put('/clients/{id}/ban', [AdminClientController::class, 'ban'])->name('admin.clients.ban');
        Route::put('/clients/{id}/unban', [AdminClientController::class, 'unban'])->name('admin.clients.unban');
    });

    // Админ (1)
    Route::middleware('can:manage-employees')->group(function () {
        Route::get('/employees/search', [AdminEmployeeController::class, 'search'])->name('admin.employees.search');
        Route::get('/employees', [AdminEmployeeController::class, 'index'])->name('admin.employees.index');
        Route::get('/employees/active', [AdminEmployeeController::class, 'active'])->name('admin.employees.active');
        Route::get('/employees/blocked', [AdminEmployeeController::class, 'blocked'])->name('admin.employees.blocked');
        Route::get('/employees/create', [AdminEmployeeController::class, 'create'])->name('admin.employees.create');
        Route::post('/employees', [AdminEmployeeController::class, 'store'])->name('admin.employees.store');
        Route::get('/employees/{id}/edit', [AdminEmployeeController::class, 'edit'])->name('admin.employees.edit');
        Route::put('/employees/{id}', [AdminEmployeeController::class, 'update'])->name('admin.employees.update');
        Route::put('/employees/{id}/ban', [AdminEmployeeController::class, 'ban'])->name('admin.employees.ban');
        Route::put('/employees/{id}/unban', [AdminEmployeeController::class, 'unban'])->name('admin.employees.unban');
        Route::delete('/employees/{id}', [AdminEmployeeController::class, 'destroy'])->name('admin.employees.destroy');
    });
});

// Клиент
Route::middleware(['auth:client'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart')->middleware(CheckClientBlocked::class);
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add')->middleware(CheckClientBlocked::class);
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update')->middleware(CheckClientBlocked::class);
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove')->middleware(CheckClientBlocked::class);
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout')->middleware(CheckClientBlocked::class);
});