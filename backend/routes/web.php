<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::resource('exercises', App\Http\Controllers\ExerciseController::class);
Route::resource('muscle-groups', App\Http\Controllers\MuscleGroupController::class);
Route::resource('profile-rest-days', App\Http\Controllers\ProfileRestDaysController::class);
Route::resource('profiles', App\Http\Controllers\ProfileController::class);
Route::resource('routines', App\Http\Controllers\RoutineController::class);
Route::resource('routine_days', App\Http\Controllers\RoutineDayController::class);
Route::resource('routine-day-exercise', App\Http\Controllers\RoutineDayExerciseController::class);
Route::resource('session-exercises', App\Http\Controllers\SessionExerciseController::class);
Route::resource('session-sets', App\Http\Controllers\SessionSetsController::class);
Route::resource('user-body-logs', App\Http\Controllers\UserBodyLogsController::class);
Route::resource('user-routine-subscriptions', App\Http\Controllers\UserRoutineSubscriptionController::class);
Route::resource('week-days', App\Http\Controllers\WeekDaysController::class);
Route::resource('workout-session-tags', App\Http\Controllers\WorkoutSessionTagController::class);
Route::resource('workout-tags', App\Http\Controllers\WorkoutTagController::class);
