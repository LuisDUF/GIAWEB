<?php

namespace App\Http\Controllers\Api\V1;
use App\Models\Profile;
use App\Http\Controllers\Controller;
use App\Models\Routine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class RoutineController extends Controller
{
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
