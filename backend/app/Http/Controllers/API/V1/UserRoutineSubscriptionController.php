<?php
namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
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

        // Usamos notación de puntos para cargar las rutinas y sus routineDays en una sola consulta optimizada
        $profile->load('subscribedRoutines.routineDays');

        return response()->json([
            'success' => true,
            'data' => $profile->subscribedRoutines
        ], 200);
    }
}
