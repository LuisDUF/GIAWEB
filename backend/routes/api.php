<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\WorkoutSessionController;
use App\Http\Controllers\Api\SessionExerciseController;
use App\Http\Controllers\Api\V1\RoutineDayController;
use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\UserRoutineSubscriptionController;
use App\Http\Controllers\API\V1\RoutineController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Rutas públicas de Autenticación
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Grupo protegido con Sanctum y prefijo v1
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {

    Route::get('/rutina/{id}', [RoutineController::class, 'show']);

    // Rutinas
    Route::get('/routine/{id}', [RoutineDayController::class, 'show']);

    // Session Exercises Resource
    Route::apiResource('session-exercises', SessionExerciseController::class);
    Route::apiResource('routine', RoutineController::class);

    // Suscripción a rutinas

    // --- RUTAS DE WORKOUT SESSIONS ---

    // 1. IMPORTANTE: Las rutas estáticas/específicas VAN PRIMERO que las que tienen {id}
    Route::get('/workout-sessions/streak', [WorkoutSessionController::class, 'streak']);
    Route::get('/workout-sessions/stats', [WorkoutSessionController::class, 'stats']);

    // 2. Creación y actualización completa (Aquí es donde entra tu guardado en 3 tablas)
    Route::post('/workout-sessions', [WorkoutSessionController::class, 'saveCompleteSession']);
    Route::put('/workout-sessions/{id}', [WorkoutSessionController::class, 'saveCompleteSession']);

    // 3. Listado y eliminación (Métodos estándar que no requieren el guardado anidado masivo)
    Route::get('/workout-sessions', [WorkoutSessionController::class, 'index']);
    Route::delete('/workout-sessions/{id}', [WorkoutSessionController::class, 'destroy']);

    // 4. Rutas con {id} al final para evitar conflictos con 'streak' o 'stats'
    Route::get('/workout-sessions/{id}', [WorkoutSessionController::class, 'show']);
    Route::get('/workout-sessions/{id}/routine', [WorkoutSessionController::class, 'routine']);
    Route::get('/workout-sessions/{id}/routine_day', [WorkoutSessionController::class, 'routine_day']);

    // ANTES: Route::get('/profiles/{id}/subscribed-routines', ...)
    // AHORA:
    Route::get('/profile/subscribed-routines', [UserRoutineSubscriptionController::class, 'getSubscribedRoutines']);
    Route::post('/profile/subscribed-routines', [UserRoutineSubscriptionController::class, 'store']);

});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [\App\Http\Controllers\API\V1\AuthController::class, 'logout']);
    Route::get('/user', [\App\Http\Controllers\API\V1\AuthController::class, 'user']);
});
