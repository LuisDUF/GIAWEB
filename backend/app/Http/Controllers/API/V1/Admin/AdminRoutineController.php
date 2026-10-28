<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Routine;
use App\Models\RoutineDay;
use App\Models\RoutineDayExercise;
use App\Models\UserRoutineSubscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminRoutineController extends Controller
{
    /**
     * Listar todas las rutinas con sus días y propietario.
     */
    public function index(Request $request): JsonResponse
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 15);

        $routines = Routine::with(['routineDays.routineDayExercises.exercise', 'user'])
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->paginate($perPage);

        return response()->json($routines);
    }

    /**
     * Ver una rutina específica.
     */
    public function show(string $id): JsonResponse
    {
        $routine = Routine::with(['routineDays.routineDayExercises.exercise', 'user'])
            ->findOrFail($id);

        return response()->json($routine);
    }

    /**
     * Actualizar campos de una rutina.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'name'              => 'sometimes|required|string|max:255',
            'description'       => 'nullable|string',
            'is_public'         => 'sometimes|boolean',
            'is_system_routine' => 'sometimes|boolean',
            'esborrada'         => 'sometimes|boolean',
        ]);

        $routine = Routine::findOrFail($id);
        $routine->update($request->only(['name', 'description', 'is_public', 'is_system_routine', 'esborrada']));

        return response()->json([
            'message' => 'Rutina actualizada correctamente.',
            'routine' => $routine->fresh(['routineDays', 'user']),
        ]);
    }

    /**
     * Eliminar una rutina y sus días/ejercicios en cascada.
     */
    public function destroy(string $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $routine = Routine::with('routineDays.routineDayExercises')->findOrFail($id);

            // 1. Eliminar suscripciones de usuarios a esta rutina
            UserRoutineSubscription::where('routine_id', $routine->id)->delete();

            // 2. Eliminar ejercicios y días de la rutina
            foreach ($routine->routineDays as $day) {
                $day->routineDayExercises()->delete();
                $day->delete();
            }

            // 3. Eliminar la rutina
            $routine->delete();
            DB::commit();

            return response()->json(['message' => 'Rutina eliminada correctamente.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al eliminar la rutina.', 'error' => $e->getMessage()], 500);
        }
    }
}
