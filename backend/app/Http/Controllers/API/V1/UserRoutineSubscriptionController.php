<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Routine;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserRoutineSubscriptionController extends Controller
{
    /**
     * Obtiene las rutinas a las que un usuario/perfil está suscrito con sus días.
     */
    public function getSubscribedRoutines(Request $request): JsonResponse
    {
        $profile = $request->user()->profile;

        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Perfil no encontrado.'
            ], 404);
        }

        $profile->load('subscribedRoutines.routineDays');

        return response()->json([
            'success' => true,
            'data' => $profile->subscribedRoutines
        ], 200);
    }

    /**
     * Crea un registro de suscripción a una rutina para el usuario autenticado.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'routine_id' => 'required|exists:routines,id',
        ]);

        $profile = $request->user()->profile;

        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Perfil de usuario no encontrado.'
            ], 404);
        }

        $routineId = $request->input('routine_id');

        // Verificar si la rutina existe
        $routine = Routine::find($routineId);
        if (!$routine) {
            return response()->json([
                'success' => false,
                'message' => 'La rutina especificada no existe.'
            ], 404);
        }

        // --- VERIFICAR SI YA ESTÁ SUSCRITO ---
        $isAlreadySubscribed = $profile->subscribedRoutines()
            ->where('routine_id', $routineId)
            ->exists();

        if ($isAlreadySubscribed) {
            return response()->json([
                'success' => false, // O true si prefieres manejarlo como aviso informativo
                'message' => 'Ya estás suscrito a esta rutina.',
            ], 409); // 409 Conflict indica que el recurso ya existe o choca con el estado actual
        }

        // Si no está suscrito, se realiza la inserción
        $profile->subscribedRoutines()->attach($routineId, [
            'saved_at' => now(),
            'is_active' => true,
        ]);

        // Obtener el registro de la tabla pivote para retornarlo
        $subscription = $profile->subscribedRoutines()->where('routine_id', $routineId)->first();

        return response()->json([
            'success' => true,
            'message' => '¡Te has suscrito con éxito!',
            'data' => $subscription
        ], 201);
    }
}
