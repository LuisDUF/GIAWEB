<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\SessionExercise;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SessionExerciseController extends Controller
{
    /**
     * Muestra una lista de todos los ejercicios de sesión.
     */
    public function index(Request $request): JsonResponse
    {
        $query = SessionExercise::with(['exercise', 'session', 'sessionSets']);

        // Opcional: Filtrar por session_id si se pasa como parámetro en la URL (?session_id=1)
        if ($request->has('session_id')) {
            $query->where('session_id', $request->session_id);
        }

        $sessionExercises = $query->get();

        return response()->json([
            'success' => true,
            'data' => $sessionExercises
        ], 200);
    }

    /**
     * Almacena un nuevo ejercicio de sesión creado.
     */
    public function store(Request $request): JsonResponse
    {
        // Validar usando las reglas definidas en el modelo
        $validatedData = $request->validate(SessionExercise::$rules);

        $sessionExercise = SessionExercise::create($validatedData);

        // Cargar relaciones para la respuesta
        $sessionExercise->load(['exercise', 'session']);

        return response()->json([
            'success' => true,
            'message' => 'Ejercicio de sesión creado exitosamente.',
            'data' => $sessionExercise
        ], 201);
    }

    /**
     * Muestra un ejercicio de sesión específico.
     */
    public function show($id): JsonResponse
    {
        $sessionExercise = SessionExercise::with(['exercise', 'session', 'sessionSets'])->find($id);

        if (!$sessionExercise) {
            return response()->json([
                'success' => false,
                'message' => 'Ejercicio de sesión no encontrado.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $sessionExercise
        ], 200);
    }

    /**
     * Actualiza un ejercicio de sesión específico.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $sessionExercise = SessionExercise::find($id);

        if (!$sessionExercise) {
            return response()->json([
                'success' => false,
                'message' => 'Ejercicio de sesión no encontrado.'
            ], 404);
        }

        // Para actualización, opcionalmente puedes hacer las reglas 'sometimes'
        // o utilizar directamente las reglas del modelo.
        $validatedData = $request->validate(SessionExercise::$rules);

        $sessionExercise->update($validatedData);

        $sessionExercise->load(['exercise', 'session', 'sessionSets']);

        return response()->json([
            'success' => true,
            'message' => 'Ejercicio de sesión actualizado exitosamente.',
            'data' => $sessionExercise
        ], 200);
    }

    /**
     * Elimina un ejercicio de sesión específico.
     */
    public function destroy($id): JsonResponse
    {
        $sessionExercise = SessionExercise::find($id);

        if (!$sessionExercise) {
            return response()->json([
                'success' => false,
                'message' => 'Ejercicio de sesión no encontrado.'
            ], 404);
        }

        $sessionExercise->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ejercicio de sesión eliminado exitosamente.'
        ], 200);
    }
}
