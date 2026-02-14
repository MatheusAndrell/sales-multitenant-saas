<?php

use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TenantController;

// Rotas de autenticação
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
});

// Rotas de tenant
Route::prefix('tenants')->group(function () {
    Route::post('/register', [TenantController::class, 'register']);
});

// Rotas de Produtos
Route::prefix('products')->group(function () {
    Route::middleware(['auth:sanctum', 'permission:manage products|manage sales'])
        ->group(function () {
            Route::get('/', [ProductController::class, 'index']);
            Route::get('/{product}', [ProductController::class, 'show']);
        });

    Route::middleware(['auth:sanctum', 'permission:manage products'])
        ->group(function () {
            Route::post('/store', [ProductController::class, 'store']);
            Route::put('/update/{product}', [ProductController::class, 'update']);
            Route::delete('/delete/{product}', [ProductController::class, 'destroy']);
        });
});

// Rotas de Customers
Route::middleware(['auth:sanctum', 'permission:manage customers'])
    ->prefix('customers')
    ->group(function () {
        Route::get('/', [CustomerController::class, 'index']);
        Route::get('/{customer}', [CustomerController::class, 'show']);
        Route::post('/store', [CustomerController::class, 'store']);
        Route::put('/update/{customer}', [CustomerController::class, 'update']);
        Route::delete('/delete/{customer}', [CustomerController::class, 'destroy']);
    });

// Rotas de Sales
Route::middleware(['auth:sanctum', 'permission:manage sales'])->group(function () {

    Route::prefix('sales')->group(function () {
        Route::get('/', [SaleController::class, 'index']);
        Route::get('/{sale}', [SaleController::class, 'show']);
        Route::post('/store', [SaleController::class, 'store']);
        Route::post('/item/{sale}', [SaleController::class, 'addItem']);
        Route::post('/pay/{sale}', [SaleController::class, 'pay']);
        Route::delete('/{sale}/items/{item}', [SaleController::class, 'removeItem']);
        Route::post('/cancel/{sale}', [SaleController::class, 'cancel']);
    });
});

// Rotas de Usuários
Route::middleware(['auth:sanctum'])
    ->prefix('users')
    ->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{user}', [UserController::class, 'show']);
        Route::post('/store', [UserController::class, 'store']);
        Route::put('/update/{user}', [UserController::class, 'update']);
        Route::delete('/delete/{user}', [UserController::class, 'destroy']);
    });

// Rotas de Relatórios
Route::middleware(['auth:sanctum'])
    ->prefix('reports')
    ->group(function () {
        Route::post('/sales', [ReportController::class, 'getSalesReport']);
        Route::post('/sales/email', [ReportController::class, 'emailSalesReport']);
    });

// Rotas de Dashboard
Route::middleware(['auth:sanctum'])
    ->prefix('dashboard')
    ->group(function () {
        Route::get('/metrics', [DashboardController::class, 'metrics']);
    });