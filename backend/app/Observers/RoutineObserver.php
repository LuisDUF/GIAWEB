<?php

namespace App\Observers;

use App\Models\Routine;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Log;

class RoutineObserver
{
    /**
     * Listener para el evento 'created' (Creación de una rutina)
     */
    public function created(Routine $routine): void
    {
        $this->logActivity('CREATE', $routine, [
            'attributes' => $routine->getAttributes(),
        ]);
    }

    /**
     * Listener para el evento 'updated' (Actualización de una rutina)
     */
    public function updated(Routine $routine): void
    {
        if ($routine->wasChanged()) {
            $this->logActivity('UPDATE', $routine, [
                'before' => array_intersect_key($routine->getOriginal(), $routine->getChanges()),
                'after'  => $routine->getChanges(),
            ]);
        }
    }

    /**
     * Listener para el evento 'deleted' (Eliminación lógica o física de una rutina)
     */
    public function deleted(Routine $routine): void
    {
        $this->logActivity('DELETE', $routine, [
            'deleted_attributes' => $routine->getOriginal(),
        ]);
    }

    /**
     * Método auxiliar privado para registrar la actividad en MongoDB sin bloquear
     * el flujo de PostgreSQL en caso de desconexión o error en NoSQL.
     */
    private function logActivity(string $action, Routine $routine, array $changes): void
    {
        try {
            ActivityLog::create([
                'timestamp'  => now()->toIso8601String(),
                'user_id'    => auth()->id() ?? $routine->user_id, // Usuario autenticado o creador de la rutina
                'action'     => $action,
                'entity'     => 'routines',
                'entity_id'  => $routine->id,
                'changes'    => $changes,
                'ip_address' => request()->ip() ?? '127.0.0.1',
                'user_agent' => request()->userAgent() ?? 'CLI/System',
            ]);
        } catch (\Throwable $e) {
            // Manejo de resiliencia según rúbrica: Si MongoDB falla, no rompe la transacción en PostgreSQL
            Log::error("Fallo al registrar bitácora en MongoDB para Routine ID {$routine->id}: " . $e->getMessage());
        }
    }
}
