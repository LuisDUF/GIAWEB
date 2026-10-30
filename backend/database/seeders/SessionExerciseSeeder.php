<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\NormalizesCsvTimestamps;
use Database\Seeders\Concerns\ResetsPrimaryKeySequence;
use Illuminate\Database\Seeder;
use App\Models\SessionExercise;
use Illuminate\Support\Facades\DB;

class SessionExerciseSeeder extends Seeder
{
    use NormalizesCsvTimestamps;
    use ResetsPrimaryKeySequence;

    public function run(): void
    {
        $filePath = database_path('seeders/data/session_exercises.csv');

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

        $items = [];
        $rawRows = [];
        $existingIds = [];
        $batchSize = 50;

        // Fase 1: Leer el CSV completo y mapear IDs válidos
        while (($data = fgetcsv($file, 1000, ',')) !== FALSE) {
            if (count($data) !== count($headers)) {
                continue;
            }

            $row = array_combine($headers, $data);
            $id = (int) $row['id'];

            $existingIds[$id] = true;
            $rawRows[] = $row;
        }
        fclose($file);

        // Fase 2: Insertar todos los ejercicios con superset_id = null
        foreach ($rawRows as $row) {
            $items[] = [
                'id'                => (int) $row['id'],
                'session_id'        => (int) $row['session_id'],
                'exercise_id'       => (int) $row['exercise_id'],
                'total_sets'        => isset($row['total_sets']) ? (int) $row['total_sets'] : 0,
                'max_weight_lifted' => !empty($row['max_weight_lifted']) ? (float) $row['max_weight_lifted'] : null,
                'min_weight_lifted' => !empty($row['min_weight_lifted']) ? (float) $row['min_weight_lifted'] : null,
                'is_skipped'        => filter_var($row['is_skipped'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'is_adhoc'          => filter_var($row['is_adhoc'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'notas'             => !empty($row['notas']) ? trim($row['notas']) : null,
                'superset_id'       => null,
                'created_at'        => $this->csvTimestamp($row['created_at'] ?? null),
                'updated_at'        => $this->csvTimestamp($row['updated_at'] ?? null),
            ];

            if (count($items) >= $batchSize) {
                $this->upsertItems($items);
                $items = [];
            }
        }

        if (!empty($items)) {
            $this->upsertItems($items);
        }

        // Fase 3: Asignar superset_id solo si la clave destino existe en la tabla
        foreach ($rawRows as $row) {
            $rowId = (int) $row['id'];
            $supersetId = !empty($row['superset_id']) ? (int) $row['superset_id'] : null;

            if ($supersetId !== null && $supersetId > 0 && isset($existingIds[$supersetId])) {
                DB::table('session_exercises')
                    ->where('id', $rowId)
                    ->update(['superset_id' => $supersetId]);
            }
        }

        // Ajustar secuencia de ID en PostgreSQL
        $maxId = SessionExercise::max('id') ?? 1;
        $this->resetPrimaryKeySequence('session_exercises', 'session_exercises_id_seq', $maxId);

        $this->command->info('¡Ejercicios de sesión cargados exitosamente!');
    }

    private function upsertItems(array $items): void
    {
        SessionExercise::upsert(
            $items,
            ['id'],
            [
                'session_id',
                'exercise_id',
                'total_sets',
                'max_weight_lifted',
                'min_weight_lifted',
                'is_skipped',
                'is_adhoc',
                'notas',
                'superset_id',
                'updated_at'
            ]
        );
    }
}
