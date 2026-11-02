<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\RoutineDay;
use Illuminate\Http\JsonResponse;

class RoutineDayController extends Controller
{
    /**
     * Obtiene la información del día de rutina y sus ejercicios para iniciar un registro.
     */
    public function show($id): JsonResponse
    {
        // Buscamos el RoutineDay con sus ejercicios y los datos de cada ejercicio
        $routineDay = RoutineDay::with(['routineDayExercises.exercise', 'routine'])
            ->findOrFail($id);

        return response()->json($routineDay);
    }
}
