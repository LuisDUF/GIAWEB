<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\ResetsPrimaryKeySequence;
use Illuminate\Database\Seeder;
use App\Models\SessionSets;
use Illuminate\Support\Facades\DB;

class SessionSetSeeder extends Seeder
{
    use ResetsPrimaryKeySequence;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = database_path('seeders/data/session_sets.csv');

        if (!file_exists($filePath)) {
            $this->command->error("El archivo CSV no existe en la ruta: {$filePath}");
            return;
        }

        $file = fopen($filePath, 'r');

        // Leer la línea de encabezados
        $headers = fgetcsv($file, 1000, ',');

        $sets = [];
        $batchSize = 100; // Procesamiento en bloques de 100 para alta densidad de datos

        while (($data = fgetcsv($file, 1000, ',')) !== FALSE) {
            $row = array_combine($headers, $data);

            $sets[] = [
                'id'                  => (int) $row['id'],
                'session_exercise_id' => (int) $row['session_exercise_id'],
                'set_number'          => (int) $row['set_number'],
                'weight_kg'           => !empty($row['weight_kg']) ? (float) $row['weight_kg'] : null,
                'reps'                => !empty($row['reps']) ? (int) $row['reps'] : null,
                'rpe'                 => !empty($row['rpe']) ? (float) $row['rpe'] : null,
                'set_type'            => !empty($row['set_type']) ? trim($row['set_type']) : 'effective',
                'sub_order'           => !empty($row['sub_order']) ? (int) $row['sub_order'] : null,
                'created_at'          => now(),
                'updated_at'          => now(),
            ];

            if (count($sets) >= $batchSize) {
                $this->upsertSets($sets);
                $sets = [];
            }
        }

        if (!empty($sets)) {
            $this->upsertSets($sets);
        }

        fclose($file);

        // Ajustar la secuencia de PostgreSQL para evitar errores de duplicidad en nuevos registros
        $maxId = SessionSets::max('id') ?? 1;
        $this->resetPrimaryKeySequence('session_sets', 'session_sets_id_seq', $maxId);

        $this->command->info('¡Series de entrenamiento cargadas exitosamente desde el CSV!');
    }

    /**
     * Método auxiliar para realizar inserción/actualización masiva (upsert)
     */
    private function upsertSets(array $sets): void
    {
        SessionSets::upsert(
            $sets,
            ['id'], // Columna en conflicto (PK)
            [
                'session_exercise_id',
                'set_number',
                'weight_kg',
                'reps',
                'rpe',
                'set_type',
                'sub_order',
                'updated_at'
            ]
        );
    }
}
