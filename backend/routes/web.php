<?php

use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Dashboard\WEB\DashboardWEBController;
use App\Http\Controllers\Home\WEB\HomeWebController;
use App\Http\Controllers\Product\WEB\ProductWebController;
use App\Http\Controllers\Sales\WEB\SalesWEBController;
use App\Http\Controllers\Transaction\WEB\TransactionWEBController;
use App\Http\Controllers\Users\WEB\UserWEBController;
use Illuminate\Support\Facades\Route;

// Guest Routes (Unauthenticated Users)
Route::group(['middleware' => 'guest'], function () {
    // Auth Routes
    Route::get('/', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login');
});

// Authenticated Routes
Route::group(['middleware' => 'auth'], function () {
    // Home
    Route::get('/home', [HomeWebController::class, 'index'])->name('home');

    // Dashboard
    Route::get('/dashboard', [DashboardWEBController::class, 'index'])->name('dashboard');

    // Products

    Route::get('/products/archive', [ProductWebController::class, 'archive'])->name('product.archive');
    // Route::get('/products/{user_id}/{isAdmin}', [ProductWebController::class, 'index'])->name('product.index');
    Route::get('/products/{user_id}', [ProductWebController::class, 'index'])->name('product.index');
    Route::post('/products/update', [ProductWebController::class, 'updateProduct'])->name('product.update');
    Route::post('/products/create', [ProductWebController::class, 'createProducts'])->name('product.create');
    Route::post('/deleteitem/{id}', [ProductWebController::class, 'deleteitem'])->name('deleteitem');
    Route::delete('/product/delete/{product_id}', [ProductWebController::class, 'deleteitem'])->name('product.delete');
    Route::get('/products/archive', [ProductWebController::class, 'archive'])->name('product.archive');
    Route::post('/products/{product_id}/restore', [ProductWebController::class, 'restore'])->name('product.restore');

    // Users
    Route::post('/users/create', [UserWEBController::class, 'store'])->name('users.store');
    Route::get('/users', [UserWEBController::class, 'index'])->name('users.index');
    Route::delete('/users/{id}', [UserWEBController::class, 'destroy'])->name('users.destroy');
    Route::put('/users/{id}', [UserWEBController::class, 'update'])->name('users.update');

    // Sales
    Route::get('/sales', [SalesWEBController::class, 'index'])->name('sales.index');
    Route::post('/update-stock', [SalesWEBController::class, 'updateStock'])->name('sales.updateStock');

    // Transactions
    Route::get('/transactions/{userId}', [TransactionWEBController::class, 'getUserTransactions']);
    Route::get('/transaction-details/{transactionId}', [TransactionWEBController::class, 'getTransactionDetails']);
    Route::get('/transactions-summary', [TransactionWEBController::class, 'getTransactionSummary']);
    Route::get('/transaction/{user_id}', [TransactionWEBController::class, 'index']);

    // Logout
    Route::get('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});

// Validation Routes (can be accessed by both authenticated and guest users)
Route::post('/check-product-image', [ProductWebController::class, 'checkProductImage'])->name('product.checkImage');

// Route::group(['middleware' => 'auth'], function () {
//     Route::get('/dashboard', [DashboardWEBController::class, 'index'])->name('dashboard');
//     Route::get('/products', [ProductWebController::class, 'index'])->name('products.index');
//     Route::get('/sales', [SalesWEBController::class, 'index'])->name('sales.index');
//     Route::get('/transaction/{user_id}', [TransactionWEBController::class, 'index'])->name('transaction.index');
//     Route::get('/users', [UserWEBController::class, 'index'])->name('users.index');
// });
