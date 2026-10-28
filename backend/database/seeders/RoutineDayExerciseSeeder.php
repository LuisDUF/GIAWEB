<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RoutineDayExercise;
use Illuminate\Support\Facades\DB;

class RoutineDayExerciseSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = database_path('seeders/data/routine_day_exercises.csv');

        if (!file_exists($filePath)) {
            $this->command->error("El archivo CSV no existe en la ruta: {$filePath}");
            return;
        }

        $file = fopen($filePath, 'r');
        $headers = fgetcsv($file, 1000, ',');

        $items = [];
        $rawRows = [];
        $existingIds = [];
        $batchSize = 50;

        // Fase 1: Leer el CSV completo y registrar los IDs válidos
        while (($data = fgetcsv($file, 1000, ',')) !== FALSE) {
            $row = array_combine($headers, $data);
            $id = (int) $row['id'];

            $existingIds[$id] = true;
            $rawRows[] = $row;
        }
        fclose($file);

        // Fase 2: Insertar todos los registros con superset_id = null
        foreach ($rawRows as $row) {
            $items[] = [
                'id'             => (int) $row['id'],
                'routine_day_id' => (int) $row['routine_day_id'],
                'exercise_id'    => (int) $row['exercise_id'],
                'order_index'    => (int) $row['order_index'],
                'superset_id'    => null,
                'created_at'     => !empty($row['created_at']) ? $row['created_at'] : now(),
                'updated_at'     => !empty($row['updated_at']) ? $row['updated_at'] : now(),
            ];

            if (count($items) >= $batchSize) {
                $this->upsertItems($items);
                $items = [];
            }
        }

        if (!empty($items)) {
            $this->upsertItems($items);
        }

        // Fase 3: Asignar superset_id solo si el ID destino realmente existe en la tabla
        foreach ($rawRows as $row) {
            $rowId = (int) $row['id'];
            $supersetId = !empty($row['superset_id']) ? (int) $row['superset_id'] : null;

            if ($supersetId !== null && isset($existingIds[$supersetId])) {
                DB::table('routine_day_exercises')
                    ->where('id', $rowId)
                    ->update(['superset_id' => $supersetId]);
            }
        }

        // Ajustar secuencia en PostgreSQL
        $maxId = RoutineDayExercise::max('id') ?? 1;
        DB::statement("SELECT setval('routine_day_exercises_id_seq', {$maxId});");

        $this->command->info('¡Ejercicios por día de rutina cargados exitosamente!');
    }

    private function upsertItems(array $items): void
    {
        RoutineDayExercise::upsert(
            $items,
            ['id'],
            ['routine_day_id', 'exercise_id', 'order_index', 'superset_id', 'updated_at']
        );
    }
}
