<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exercise;
use App\Models\MuscleGroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminExerciseController extends Controller
{
    /**
     * Listar todos los ejercicios con su grupo muscular.
     */
    public function index(Request $request): JsonResponse
    {
        $search  = $request->input('search');
        $perPage = $request->input('per_page', 15);

        $exercises = Exercise::with('muscleGroup')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->paginate($perPage);

        return response()->json($exercises);
    }

    /**
     * Actualizar campos de un ejercicio.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'name'            => 'sometimes|required|string|max:255',
            'description'     => 'nullable|string',
            'muscle_group_id' => 'sometimes|exists:muscle_groups,id',
            'equipment_type'  => 'nullable|string|max:100',
            'maquina'         => 'nullable|string|max:100',
            'con_asiento'     => 'nullable|boolean',
            'con_respaldo'    => 'nullable|boolean',
        ]);

        $exercise = Exercise::findOrFail($id);
        $exercise->update($request->only([
            'name', 'description', 'muscle_group_id',
            'equipment_type', 'maquina', 'con_asiento', 'con_respaldo',
        ]));

        return response()->json([
            'message'  => 'Ejercicio actualizado correctamente.',
            'exercise' => $exercise->fresh('muscleGroup'),
        ]);
    }

    /**
     * Eliminar un ejercicio.
     */
    public function destroy(int $id): JsonResponse
    {
        $exercise = Exercise::findOrFail($id);
        $exercise->delete();

        return response()->json(['message' => 'Ejercicio eliminado correctamente.']);
    }

    /**
     * Listar grupos musculares (para el select del modal de edición).
     */
    public function muscleGroups(): JsonResponse
    {
        return response()->json(MuscleGroup::all());
    }
}
