<?php

use Illuminate\Http\Request;
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

Route::get('/ejemplo', function () {
    return 'Hola';
});

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;


//Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [UserController::class, 'store']);


// Rutas protegidas por autenticación
Route::middleware(['auth:api', 'checkAuthToken'])->group(function () {
    // Rutas de API v1
    Route::prefix('v1')->group(function () {
        // CRUD de usuarios
        Route::resource('users', 'UserController')->except(['create', 'edit']);

        //Listar un usuario
        Route::get('users', [UserController::class, 'index']);
        Route::get('users/{user}', [UserController::class, 'show']);

        // Rutas adicionales para actualizar, activar, desactivar y eliminar usuarios
        Route::put('users/{user}', [UserController::class, 'update']);
        Route::put('users/{user}/status/{status}',  [UserController::class, 'status']);
        Route::delete('users/{user}',  [UserController::class, 'destroy']);

        // Ruta para refrescar el token
        Route::post('refresh',  [AuthController::class, 'refresh']);


        // Ruta para obtener el usuario autenticado
        Route::get('/user', function (Request $request) {
            return $request->user();
        })->middleware('auth:api');
    });
});

