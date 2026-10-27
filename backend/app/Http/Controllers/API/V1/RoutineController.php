<?php

namespace App\Http\Controllers\Api\V1;
use App\Models\Profile;
use App\Http\Controllers\Controller;
use App\Models\Routine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\RoutineDay;
use App\Models\RoutineDayExercise;
class RoutineController extends Controller
{

public function store(Request $request): JsonResponse
{
    $request->validate([
        'rutina_name' => 'required|string|max:255',
        'routine_days' => 'required|array|min:1',
        'routine_days.*.name' => 'required|string|max:255',
        'routine_days.*.day_order' => 'required|integer',
        'routine_days.*.exercises' => 'array',
        'routine_days.*.exercises.*.id' => 'required|exists:exercises,id',
    ]);

    DB::beginTransaction();

    try {
        // Obtenemos el ID UUID del perfil relacionado al usuario autenticado
        $profileId = $request->user()->profile->id ?? null;

        if (!$profileId) {
            return response()->json([
                'message' => 'El usuario autenticado no tiene un perfil asociado.'
            ], 422);
        }

        $routine = Routine::create([
            'user_id' => $profileId, // Aquí se pasa el UUID correcto de la tabla profiles
            'name' => $request->input('rutina_name'),
            'description' => null,
            'is_public' => true,
            'is_system_routine' => false,
            'esborrada' => false,
        ]);

        foreach ($request->input('routine_days', []) as $dayData) {
            $routineDay = RoutineDay::create([
                'routine_id' => $routine->id,
                'name' => $dayData['name'],
                'day_order' => $dayData['day_order'],
            ]);

// 3. Registrar los ejercicios asociados a cada día si existen
            if (!empty($dayData['exercises'])) {
                foreach ($dayData['exercises'] as $index => $exercise) {
                    RoutineDayExercise::create([
                        'routine_day_id' => $routineDay->id,
                        'exercise_id' => $exercise['id'],
                        'order_index' => $index + 1, // Se asigna el orden secuencial del ejercicio
                    ]);
                }
            }
        }

        DB::commit();

        return response()->json([
            'message' => 'Rutina registrada exitosamente',
            'routine' => $routine->load('routineDays.routineDayExercises.exercise')
        ], 201);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'message' => 'Error al registrar la rutina',
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], 500);
    }
}


    /**
     * Obtiene la información del día de rutina y sus ejercicios para iniciar un registro.
     */
public function index(Request $request): JsonResponse
{
    $search = $request->input('search');

    $routines = Routine::with(['user'])
        ->when($search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('username', 'like', "%{$search}%"); // Cambiado de 'name' a 'username'
                  });
        })
        ->paginate(10);

    return response()->json($routines);
}

   public function show(string $id)
    {
        $session = Routine::with([
        'routineDays', 'user'
    ])
    ->findOrFail($id);

        return response()->json($session);
    }

}
