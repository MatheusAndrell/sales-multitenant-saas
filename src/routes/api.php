<?php

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
