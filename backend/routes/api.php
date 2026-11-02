<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\WorkoutSessionController;
use App\Http\Controllers\API\V1\SessionExerciseController;
use App\Http\Controllers\API\V1\RoutineDayController;
use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\UserRoutineSubscriptionController;
use App\Http\Controllers\API\V1\RoutineController;
use App\Http\Controllers\API\V1\ExerciseController;
use App\Models\ActivityLog;
use App\Http\Controllers\API\HealthController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Rutas públicas de Autenticación
Route::get('/health', [HealthController::class, 'health']);
Route::get('/ready', [HealthController::class, 'ready']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Grupo protegido con Sanctum y prefijo v1
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
        Route::get('/routine/{id}', [RoutineDayController::class, 'show']);
    Route::apiResource('routine', RoutineController::class);


    Route::get('/muscle-groups/{muscleGroupId}/exercises', [ExerciseController::class, 'getByMuscleGroup']);


    Route::get('/rutina/{id}', [RoutineController::class, 'show']);

    // Rutinas


    // Session Exercises Resource
    Route::apiResource('session-exercises', SessionExerciseController::class);

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

    Route::get('/activity-logs', function () {
    return response()->json(ActivityLog::latest()->take(20)->get());
});

});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [\App\Http\Controllers\API\V1\AuthController::class, 'logout']);
    Route::get('/user', [\App\Http\Controllers\API\V1\AuthController::class, 'user']);
});

// Rutas exclusivas de administrador
Route::middleware(['auth:sanctum', 'is_admin'])->prefix('v1/admin')->group(function () {
    // Gestión de rutinas
    Route::get('/routines',          [\App\Http\Controllers\API\V1\Admin\AdminRoutineController::class, 'index']);
    Route::get('/routines/{id}',     [\App\Http\Controllers\API\V1\Admin\AdminRoutineController::class, 'show']);
    Route::put('/routines/{id}',     [\App\Http\Controllers\API\V1\Admin\AdminRoutineController::class, 'update']);
    Route::delete('/routines/{id}',  [\App\Http\Controllers\API\V1\Admin\AdminRoutineController::class, 'destroy']);

    // Gestión de ejercicios
    Route::get('/exercises',         [\App\Http\Controllers\API\V1\Admin\AdminExerciseController::class, 'index']);
    Route::put('/exercises/{id}',    [\App\Http\Controllers\API\V1\Admin\AdminExerciseController::class, 'update']);
    Route::delete('/exercises/{id}', [\App\Http\Controllers\API\V1\Admin\AdminExerciseController::class, 'destroy']);
    Route::get('/muscle-groups',     [\App\Http\Controllers\API\V1\Admin\AdminExerciseController::class, 'muscleGroups']);
});

