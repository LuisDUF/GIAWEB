<?php

namespace App\Observers;

use App\Models\Exercise;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Log;

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
        try {
            ActivityLog::create([
                'timestamp'  => now()->toIso8601String(),
                'user_id'    => auth()->id(), // ID del usuario/admin que realiza la modificación
                'action'     => $action,
                'entity'     => 'exercises',
                'entity_id'  => $exercise->id,
                'changes'    => $changes,
                'ip_address' => request()->ip() ?? '127.0.0.1',
                'user_agent' => request()->userAgent() ?? 'CLI/System',
            ]);
        } catch (\Throwable $e) {
            // Estrategia de resiliencia: Si MongoDB falla, no detiene la operación en PostgreSQL
            Log::error("Fallo al registrar bitácora en MongoDB para Exercise ID {$exercise->id}: " . $e->getMessage());
        }
    }
}
