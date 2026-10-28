<?php

namespace App\Observers;

use App\Models\WorkoutSession;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Log;

class WorkoutSessionObserver
{
    /**
     * Listener para el evento 'created' (Creación de sesión de entrenamiento)
     */
    public function created(WorkoutSession $workoutSession): void
    {
        $this->logActivity('CREATE', $workoutSession, [
            'attributes' => $workoutSession->getAttributes(),
        ]);
    }

    /**
     * Listener para el evento 'updated' (Actualización de sesión)
     */
    public function updated(WorkoutSession $workoutSession): void
    {
        // Solo registramos si realmente hubo cambios en los atributos
        if ($workoutSession->wasChanged()) {
            $this->logActivity('UPDATE', $workoutSession, [
                'before' => array_intersect_key($workoutSession->getOriginal(), $workoutSession->getChanges()),
                'after'  => $workoutSession->getChanges(),
            ]);
        }
    }

    /**
     * Listener para el evento 'deleted' (Eliminación de sesión)
     */
    public function deleted(WorkoutSession $workoutSession): void
    {
        $this->logActivity('DELETE', $workoutSession, [
            'deleted_attributes' => $workoutSession->getOriginal(),
        ]);
    }

    /**
     * Método auxiliar privado para insertar el documento en MongoDB sin interrumpir
     * el flujo principal de PostgreSQL en caso de fallo en la red/NoSQL.
     */
    private function logActivity(string $action, WorkoutSession $workoutSession, array $changes): void
    {
        try {
            ActivityLog::create([
                'timestamp'  => now()->toIso8601String(),
                'user_id'    => auth()->id() ?? $workoutSession->user_id, // Asigna el usuario autenticado o el dueño de la sesión
                'action'     => $action,
                'entity'     => 'workout_sessions',
                'entity_id'  => $workoutSession->id,
                'changes'    => $changes,
                'ip_address' => request()->ip() ?? '127.0.0.1',
                'user_agent' => request()->userAgent() ?? 'CLI/System',
            ]);
        } catch (\Throwable $e) {
            // Manejo de resiliencia: Si MongoDB falla, no rompe la transacción de PostgreSQL
            Log::error("Fallo al escribir bitácora en MongoDB para WorkoutSession ID {$workoutSession->id}: " . $e->getMessage());
        }
    }
}
