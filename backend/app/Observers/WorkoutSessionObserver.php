<?php

namespace App\Observers;

use App\Models\WorkoutSession;
use App\Services\ActivityOutboxService;

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
     * Persists to the relational outbox; the queue and reconciler deliver to MongoDB.
     */
    private function logActivity(string $action, WorkoutSession $workoutSession, array $changes): void
    {
        app(ActivityOutboxService::class)->record($action, $workoutSession, 'workout_sessions', $changes);
    }
}
