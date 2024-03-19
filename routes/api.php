<?php

use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckAuthToken;

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [UserController::class, 'store']);

// Rutas protegidas por autenticación
Route::middleware([CheckAuthToken::class])->group(function () {
    // Ruta para cerrar sesión
    Route::post('logout', [AuthController::class, 'logout']);
    // Ruta para refrescar el token
    Route::post('refresh',  [AuthController::class, 'refresh']);
    // Rutas de API v1
    Route::prefix('v1')->group(function () {
        // CRUD de usuarios
        Route::apiResource('users', UserController::class);
    });
});

