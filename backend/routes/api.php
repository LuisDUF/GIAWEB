<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\WorkoutSessionController;
use App\Http\Controllers\Api\SessionExerciseController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/




Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    // Si ya tienes un resource o rutas agrupadas, asegúrate de que quede accesible en /api/v1/workout-sessions/stats
Route::apiResource('session-exercises', SessionExerciseController::class);

    Route::get('/workout-sessions/streak', [\App\Http\Controllers\API\V1\WorkoutSessionController::class, 'streak']);
    Route::get('/workout-sessions/stats', [\App\Http\Controllers\API\V1\WorkoutSessionController::class, 'stats']);
    Route::get('/workout-sessions/{id}/routine', [WorkoutSessionController::class, 'routine']);
    Route::get('/workout-sessions/{id}/routine_day', [WorkoutSessionController::class, 'routine_day']);
});



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [\App\Http\Controllers\API\V1\AuthController::class, 'login']);
Route::post('/register', [\App\Http\Controllers\API\V1\AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [\App\Http\Controllers\API\V1\AuthController::class, 'logout']);
    Route::get('/user', [\App\Http\Controllers\API\V1\AuthController::class, 'user']);

    Route::apiResource('v1/workout-sessions', \App\Http\Controllers\API\V1\WorkoutSessionController::class)
        ->except(['create', 'edit']);



});

