<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\WorkoutSession;
use Illuminate\Http\Request;
use Carbon\Carbon;

class WorkoutSessionController extends Controller
{




public function streak(Request $request)
{
    $profile = $request->user()->profile;

    if (!$profile) {
        return response()->json(['current_streak' => 0, 'max_streak' => 0]);
    }

    // Obtenemos las fechas únicas ordenadas de más reciente a más antigua
    $dates = $profile->workoutSessions()
        ->orderBy('date', 'desc')
        ->pluck('date')
        ->map(fn($date) => Carbon::parse($date)->toDateString())
        ->unique()
        ->values();

    if ($dates->isEmpty()) {
        return response()->json(['current_streak' => 0, 'max_streak' => 0]);
    }

    $maxStreak = 0;
    $tempStreak = 0;
    $today = Carbon::today();
    $lastWorkout = Carbon::parse($dates[0]);

    // La racha está activa si entrenó hoy o ayer
    $isActive = $lastWorkout->isSameDay($today) || $lastWorkout->isSameDay($today->copy()->subDay());

    // 1. Calcular Racha Máxima Histórica
    for ($i = 0; $i < count($dates); $i++) {
        if ($i === 0) {
            $tempStreak = 1;
        } else {
            $prevDate = Carbon::parse($dates[$i - 1]);
            $currDate = Carbon::parse($dates[$i]);

            if ($prevDate->copy()->subDay()->isSameDay($currDate)) {
                $tempStreak++;
            } elseif ($prevDate->isSameDay($currDate)) {
                continue; // Ignorar duplicados del mismo día
            } else {
                $tempStreak = 1; // Se rompió la racha, reiniciamos conteo temporal
            }
        }

        if ($tempStreak > $maxStreak) {
            $maxStreak = $tempStreak;
        }
    }

    // 2. Calcular Racha Actual
    $currentStreak = 0;
    if ($isActive) {
        // Si entrenó hoy, empezamos a contar desde hoy; si no, desde ayer
        $expectedDate = $lastWorkout->isSameDay($today) ? $today->copy() : $today->copy()->subDay();

        foreach ($dates as $dateStr) {
            $d = Carbon::parse($dateStr);
            if ($d->isSameDay($expectedDate)) {
                $currentStreak++;
                $expectedDate->subDay();
            } else {
                break;
            }
        }
    }

    return response()->json([
        'current_streak' => $currentStreak,
        'max_streak' => max($maxStreak, $currentStreak),
    ]);
}

public function index(Request $request)
{
    $validated = $request->validate([
        'date' => ['nullable', 'date'],
    ]);

    $sessions = $this->profileFor($request)
        ->workoutSessions()
        ->with(['routineDay.routine']) // <-- Añadimos esto para cargar la relación anidada
        ->when(
            $validated['date'] ?? null,
            fn ($query, $date) => $query->whereDate('date', $date)
        )
        ->latest('date')
        ->paginate(12);

    return response()->json($sessions);
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['prohibited'],
            'routine_day_id' => ['nullable'],
            'date' => ['required', 'date'],
            'duration_minutes' => ['nullable', 'integer', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $session = $this->profileFor($request)
            ->workoutSessions()
            ->create($validated);

        return response()->json($session, 201);
    }

    public function show(Request $request, string $id)
    {
        $session = $this->profileFor($request)
    ->workoutSessions()
    ->with([
        'routineDay.routine',
        'sessionExercises.exercise',
        'sessionExercises.sessionSets',
    ])
    ->findOrFail($id);

        return response()->json($session);
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'user_id' => ['prohibited'],
            'routine_day_id' => ['sometimes', 'nullable'],
            'date' => ['sometimes', 'required', 'date'],
            'duration_minutes' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'notes' => ['sometimes', 'nullable', 'string'],
        ]);

        $session = $this->profileFor($request)
            ->workoutSessions()
            ->findOrFail($id);
        $session->update($validated);

        return response()->json($session->refresh());
    }

    public function destroy(Request $request, string $id)
    {
        $session = $this->profileFor($request)
            ->workoutSessions()
            ->findOrFail($id);
        $session->delete();

        return response()->json(['message' => 'Sesión eliminada correctamente.']);
    }

    private function profileFor(Request $request): Profile
    {
        $profile = $request->user()->profile;

        abort_if($profile === null, 409, 'La cuenta no tiene un perfil asociado.');

        return $profile;
    }

    // En tu Controlador de Laravel
public function stats(Request $request)
{
    // 1. Mantenemos tu forma original de obtener las sesiones del perfil
    $sessions = $this->profileFor($request)
        ->workoutSessions()
        ->get();

    // 2. Capturamos el filtro que viene por la URL (por defecto será 'dia')
    $filtro = $request->input('filtro', 'dia');

    // 3. Evaluamos qué tipo de conteo realizar
    if ($filtro === 'semana') {
        $conteo = $sessions->groupBy(function($session) {
            return 'Semana ' . \Carbon\Carbon::parse($session->date)->weekOfMonth;
        })->map->count();

    } elseif ($filtro === 'mes') {
        $diasMeses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        $conteo = $sessions->groupBy(function($session) use ($diasMeses) {
            $mesIndex = \Carbon\Carbon::parse($session->date)->month - 1;
            return $diasMeses[$mesIndex];
        })->map->count();

    } elseif ($filtro === 'anio') {
        $conteo = $sessions->groupBy(function($session) {
            return \Carbon\Carbon::parse($session->date)->year;
        })->map->count();

    } else {
        // Por defecto: Agrupado por día de la semana (tu lógica original que sí funcionaba)
        $diasSemana = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

        $conteo = $sessions->groupBy(function($session) use ($diasSemana) {
            $numeroDia = \Carbon\Carbon::parse($session->date)->dayOfWeek; // 0 (Domingo) a 6 (Sábado)
            return $diasSemana[$numeroDia];
        })->map->count();
    }

    return response()->json($conteo);
}


/**
     * Obtener la información de la rutina y sus días asociados a partir de una sesión de entrenamiento.
     */
public function routine(Request $request, string $id)
{
    $session = $this->profileFor($request)
        ->workoutSessions()
        ->with(['routineDay.routine'])
        ->findOrFail($id);

    // Si no tiene rutina, devolvemos null con código 200 en lugar de abortar con 404
    if (!$session->routineDay || !$session->routineDay->routine) {
        return response()->json(null, 200);
    }

    return response()->json($session->routineDay->routine);
}

public function routine_day(Request $request, string $id)
{
    $session = $this->profileFor($request)
        ->workoutSessions()
        ->with(['routineDay.routine'])
        ->findOrFail($id);

    // Si no tiene día de rutina asociado, retornamos null
    if (!$session->routineDay) {
        return response()->json(null, 200);
    }

    // Retornamos un array estructurado con ambos datos
    return response()->json([
        'routine_day' => $session->routineDay,
        'routine'     => $session->routineDay->routine,
    ], 200);
}
}
