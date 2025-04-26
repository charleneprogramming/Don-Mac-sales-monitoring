<?php

use App\Http\Controllers\Auth\API\AuthAPIController;
use App\Http\Controllers\Dashboard\API\DashboardAPIController;
use App\Http\Controllers\Product\API\ProductAPIController;
use App\Http\Controllers\Sales\API\SalesAPIController;
use App\Http\Controllers\Transaction\API\TransactionAPIController;
use App\Http\Controllers\Users\API\UsersAPIController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/login', [AuthAPIController::class, 'login']);

// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {
    // Auth routes
    Route::post('/logout', [AuthAPIController::class, 'logout']);
    Route::get('/user', [AuthAPIController::class, 'user']);

    // Product routes
    Route::get('/products/{user_id}', [ProductAPIController::class, 'findByUserID']);
    Route::post('/add/products', [ProductAPIController::class, 'createProducts']);
    Route::post('/update/products', [ProductAPIController::class, 'updateProduct']);
    Route::delete('/delete/products/{id}', [ProductAPIController::class, 'destroy']);

    // Sales routes
    Route::post('/createSales', [SalesAPIController::class, 'createSales']);
    Route::get('/sales', [SalesAPIController::class, 'findAll']);
    Route::get('/sales/{userID}', [SalesAPIController::class, 'findByUserID']);
    Route::get('/sales/products/{userId}', [SalesAPIController::class, 'getProducts']);

    // User routes
    Route::get('/display/users', [UsersAPIController::class, 'findAll']);
    Route::post('/create/users', [UsersAPIController::class, 'create']);
    Route::put('/update/users/{id}', [UsersAPIController::class, 'update']);
    Route::delete('/delete/users/{id}', [UsersAPIController::class, 'destroy']);
    Route::get('/users/{username}', [UsersAPIController::class, 'findByUsername']);

    // Transaction routes
    Route::get('/transactions', [TransactionAPIController::class, 'index']);

    // Dashboard routes
    Route::get('/dashboard', [DashboardAPIController::class, 'getDashboardData']);
});
