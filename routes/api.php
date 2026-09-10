<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LayoutController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:6,1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/layout', [LayoutController::class, 'index']);

    // Nama route diberi awalan "api." agar tidak bentrok dengan resource
    // "users"/"roles" milik panel admin (routes/backend.php). Tanpa ini,
    // route('users.store')/route('roles.index') di panel admin diam-diam
    // mengarah ke endpoint API karena berkas ini didaftarkan belakangan.
    Route::apiResource('users', UserController::class)->names('api.users');
    Route::apiResource('roles', RoleController::class)->names('api.roles');
});
