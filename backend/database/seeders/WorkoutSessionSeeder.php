<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\NormalizesCsvTimestamps;
use Database\Seeders\Concerns\ResetsPrimaryKeySequence;
use Illuminate\Database\Seeder;
use App\Models\WorkoutSession;
use Illuminate\Support\Facades\DB;

class WorkoutSessionSeeder extends Seeder
{
    use NormalizesCsvTimestamps;
    use ResetsPrimaryKeySequence;

    public function run(): void
    {
        $filePath = database_path('seeders/data/workout_sessions.csv');

        if (!file_exists($filePath)) {
            $this->command->error("El archivo CSV no existe en la ruta: {$filePath}");
            return;
        }

        $file = fopen($filePath, 'r');

        $rawHeaders = fgetcsv($file, 1000, ',');
        if (!$rawHeaders) {
            fclose($file);
            return;
        }

        $headers = array_map(function ($header) {
            return preg_replace('/[\x00-\x1F\x7F\xEF\xBB\xBF]/', '', trim($header));
        }, $rawHeaders);

        $sessions = [];
        $batchSize = 50;

        while (($data = fgetcsv($file, 1000, ',')) !== FALSE) {
            if (count($data) !== count($headers)) {
                continue;
            }

            $row = array_combine($headers, $data);

            // Obtener el UUID del perfil (soporta si en el CSV viene como profile_id o user_id)
            $userId = !empty($row['user_id']) ? trim($row['user_id']) : (!empty($row['profile_id']) ? trim($row['profile_id']) : null);

            // Convertir '103' al UUID real si el CSV conserva referencias antiguas
            if ($userId === '103' || $userId === '1') {
                $userId = '0103d5bf-01fb-44a2-af12-8ca1e277d6bd';
            }

            $routineDayId = !empty($row['routine_day_id']) && (int) $row['routine_day_id'] > 0
                ? (int) $row['routine_day_id']
                : null;

            $sessions[] = [
                'id'               => (int) $row['id'],
                'user_id'          => $userId,
                'routine_day_id'   => $routineDayId,
                'date'             => $this->csvTimestamp($row['date'] ?? null),
                'duration_minutes' => !empty($row['duration_minutes']) ? (int) $row['duration_minutes'] : null,
                'notes'            => !empty($row['notes']) ? trim($row['notes']) : null,
                'created_at'       => $this->csvTimestamp($row['created_at'] ?? null),
                'updated_at'       => $this->csvTimestamp($row['updated_at'] ?? null),
            ];

            if (count($sessions) >= $batchSize) {
                $this->upsertSessions($sessions);
                $sessions = [];
            }
        }

        if (!empty($sessions)) {
            $this->upsertSessions($sessions);
        }

        fclose($file);

        // Ajustar secuencia de ID en PostgreSQL
        $maxId = WorkoutSession::max('id') ?? 1;
        $this->resetPrimaryKeySequence('workout_sessions', 'workout_sessions_id_seq', $maxId);

        $this->command->info('¡Sesiones de entrenamiento cargadas exitosamente!');
    }

    private function upsertSessions(array $sessions): void
    {
        WorkoutSession::upsert(
            $sessions,
            ['id'],
            ['user_id', 'routine_day_id', 'date', 'duration_minutes', 'notes', 'updated_at']
        );
    }
}
