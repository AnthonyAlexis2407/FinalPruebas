<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect()->route('products.index');
});

// Auth Routes
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Operational Routes (Available to everyone authenticated)
    Route::get('products', [ProductController::class, 'index'])->name('products.index');

    Route::get('/inventory/{product}/manage', [InventoryController::class, 'index'])->name('inventory.index');
    Route::post('/inventory/{product}/manage', [InventoryController::class, 'store'])->name('inventory.store');

    Route::resource('products.recipes', \App\Http\Controllers\RecipeController::class)->only(['index', 'store', 'destroy']);
    Route::resource('sizes', \App\Http\Controllers\SizeController::class);

    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');

    Route::get('pos', [SaleController::class, 'index'])->name('sales.pos');
    Route::post('sales', [SaleController::class, 'store'])->name('sales.store');
    Route::get('sales/{sale}/print', [SaleController::class, 'print'])->name('sales.print');
    Route::get('sales/{sale}/ticket', [SaleController::class, 'ticket'])->name('sales.ticket');

    Route::get('users', [AuthController::class, 'index'])->name('users.index');

    // Admin-Only Management Routes
    Route::middleware(['admin'])->group(function () {
        // Product Management (Creative/Destructive)
        Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('products', [ProductController::class, 'store'])->name('products.store');
        Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

        // Inventory Management (Destructive)
        Route::delete('/inventory/{product}/manage/{productSize}', [InventoryController::class, 'destroy'])->name('inventory.destroy');

        // Category Management (Modifications)
        Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        // User Management (Creative/Destructive)
        Route::get('register', [AuthController::class, 'showRegister'])->name('register');
        Route::post('register', [AuthController::class, 'register']);
        Route::delete('users/{user}', [AuthController::class, 'destroy'])->name('users.destroy');

        // Store Switching
        Route::get('/switch-store/{store}', function (\App\Models\Store $store) {
            $storeId = $store->id;
            Auth::logout();
            session(['active_store_id' => $storeId]);
            return redirect()->route('login');
        })->name('stores.switch');
    });

    // Secondary/Wildcard Routes (Place after specific static routes)
    Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');

    // Reports
    Route::get('reports/daily', [ReportController::class, 'daily'])->name('reports.daily');
    Route::get('reports/monthly', [ReportController::class, 'monthly'])->name('reports.monthly');
});
