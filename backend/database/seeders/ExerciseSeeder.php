<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exercise;
use Illuminate\Support\Facades\DB;

class ExerciseSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = database_path('seeders/data/exercises.csv');

        if (!file_exists($filePath)) {
            $this->command->error("El archivo CSV no existe en la ruta: {$filePath}");
            return;
        }

        $file = fopen($filePath, 'r');
        $headers = fgetcsv($file, 1000, ',');

        $exercises = [];
        $batchSize = 50;

        while (($data = fgetcsv($file, 1000, ',')) !== FALSE) {
            $row = array_combine($headers, $data);

            $exercises[] = [
                'id'              => (int) $row['id'],
                'name'            => trim($row['name']),
                'description'     => !empty($row['description']) ? trim($row['description']) : null,
                'image_url'       => !empty($row['image_url']) ? trim($row['image_url']) : null,
                'animacion'       => !empty($row['animacion']) ? trim($row['animacion']) : null,
                'equipment_type'  => !empty($row['equipment_type']) ? trim($row['equipment_type']) : null,
                'maquina'         => !empty($row['maquina']) ? trim($row['maquina']) : null,
                'con_asiento'     => $row['con_asiento'] === '' ? null : filter_var($row['con_asiento'], FILTER_VALIDATE_BOOLEAN),
                'con_respaldo'    => $row['con_respaldo'] === '' ? null : filter_var($row['con_respaldo'], FILTER_VALIDATE_BOOLEAN),
                'muscle_group_id' => (int) $row['muscle_group_id'],
                'created_at'      => now(),
                'updated_at'      => now(),
            ];

            if (count($exercises) >= $batchSize) {
                $this->upsertExercises($exercises);
                $exercises = [];
            }
        }

        if (!empty($exercises)) {
            $this->upsertExercises($exercises);
        }

        fclose($file);

        // Actualizar la secuencia de la llave primaria en PostgreSQL
        $maxId = Exercise::max('id') ?? 1;
        DB::statement("SELECT setval('exercises_id_seq', {$maxId});");

        $this->command->info('¡Ejercicios cargados exitosamente desde el CSV!');
    }

    private function upsertExercises(array $exercises): void
    {
        Exercise::upsert(
            $exercises,
            ['id'],
            [
                'name',
                'description',
                'image_url',
                'animacion',
                'equipment_type',
                'maquina',
                'con_asiento',
                'con_respaldo',
                'muscle_group_id',
                'updated_at',
            ]
        );
    }
}
