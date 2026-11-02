<?php

namespace App\Observers;

use App\Models\Exercise;
use App\Services\ActivityOutboxService;

class ExerciseObserver
{
    /**
     * Listener para el evento 'created' (Creación de un ejercicio)
     */
    public function created(Exercise $exercise): void
    {
        $this->logActivity('CREATE', $exercise, [
            'attributes' => $exercise->getAttributes(),
        ]);
    }

    /**
     * Listener para el evento 'updated' (Actualización de un ejercicio)
     */
    public function updated(Exercise $exercise): void
    {
        if ($exercise->wasChanged()) {
            $this->logActivity('UPDATE', $exercise, [
                'before' => array_intersect_key($exercise->getOriginal(), $exercise->getChanges()),
                'after'  => $exercise->getChanges(),
            ]);
        }
    }

    /**
     * Listener para el evento 'deleted' (Eliminación lógica o física de un ejercicio)
     */
    public function deleted(Exercise $exercise): void
    {
        $this->logActivity('DELETE', $exercise, [
            'deleted_attributes' => $exercise->getOriginal(),
        ]);
    }

    /**
     * Método auxiliar privado para registrar la actividad en MongoDB sin bloquear
     * la transacción principal de PostgreSQL en caso de fallo en NoSQL.
     */
    private function logActivity(string $action, Exercise $exercise, array $changes): void
    {
        app(ActivityOutboxService::class)->record($action, $exercise, 'exercises', $changes);
    }
}
