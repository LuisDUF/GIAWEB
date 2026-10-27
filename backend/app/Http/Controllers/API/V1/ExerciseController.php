<?php

namespace App\Http\Controllers\Api\V1;
use App\Models\Exercise;
use App\Models\Routine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class ExerciseController extends \App\Http\Controllers\Controller{

public function getByMuscleGroup($muscleGroupId)
{
    // Validar que el ID sea numérico para evitar errores de base de datos
    if (!is_numeric($muscleGroupId)) {
        return response()->json([
            'success' => false,
            'message' => 'ID de grupo muscular inválido.'
        ], 400);
    }

    $exercises = Exercise::where('muscle_group_id', $muscleGroupId)->get();

    return response()->json($exercises, 200);
}

}
