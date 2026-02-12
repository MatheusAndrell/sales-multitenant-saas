<?php

use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TenantRegistrationController;

// Rotas de autenticação
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
});

// Rotas de tenant
Route::prefix('tenants')->group(function () {
    Route::post('/register', [TenantRegistrationController::class, 'register']);
});

// Rotas de Produtos
Route::middleware(['auth:sanctum', 'permission:manage products'])
    ->prefix('products')
    ->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::post('/store', [ProductController::class, 'store']);
        Route::put('/update/{product}', [ProductController::class, 'update']);
        Route::delete('/delete/{product}', [ProductController::class, 'destroy']);
    });

// Rotas de Customers
    Route::middleware(['auth:sanctum', 'permission:manage customers'])
    ->prefix('customers')
    ->group(function () {
        Route::get('/', [CustomerController::class, 'index']);
        Route::post('/store', [CustomerController::class, 'store']);
        Route::put('/update/{customer}', [CustomerController::class, 'update']);
        Route::delete('/delete/{customer}', [CustomerController::class, 'destroy']);
    });
