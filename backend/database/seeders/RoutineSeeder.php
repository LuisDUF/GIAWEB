<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Routine;
use Illuminate\Support\Facades\DB;

class RoutineSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = database_path('seeders/data/routines.csv');

        if (!file_exists($filePath)) {
            $this->command->error("El archivo CSV no existe en la ruta: {$filePath}");
            return;
        }

        $file = fopen($filePath, 'r');
        $headers = fgetcsv($file, 1000, ',');

        $routines = [];
        $batchSize = 50;

        while (($data = fgetcsv($file, 1000, ',')) !== FALSE) {
            $row = array_combine($headers, $data);

            // Soporta si el encabezado del CSV dice 'profile_id' o 'user_id'
            $userId = !empty($row['user_id']) ? trim($row['user_id']) : (!empty($row['profile_id']) ? trim($row['profile_id']) : null);

            $routines[] = [
                'id'                => (int) $row['id'],
                'user_id'           => $userId,
                'name'              => trim($row['name']),
                'description'       => !empty($row['description']) ? trim($row['description']) : null,
                'is_public'         => filter_var($row['is_public'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'is_system_routine' => filter_var($row['is_system_routine'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'esborrada'         => filter_var($row['esborrada'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'created_at'        => !empty($row['created_at']) ? $row['created_at'] : now(),
                'updated_at'        => !empty($row['updated_at']) ? $row['updated_at'] : now(),
            ];

            if (count($routines) >= $batchSize) {
                $this->upsertRoutines($routines);
                $routines = [];
            }
        }

        if (!empty($routines)) {
            $this->upsertRoutines($routines);
        }

        fclose($file);

        // Ajustar secuencia de PostgreSQL para el ID de las rutinas
        $maxId = Routine::max('id') ?? 1;
        DB::statement("SELECT setval('routines_id_seq', {$maxId});");

        $this->command->info('¡Rutinas cargadas exitosamente!');
    }

    private function upsertRoutines(array $routines): void
    {
        Routine::upsert(
            $routines,
            ['id'],
            ['user_id', 'name', 'description', 'is_public', 'is_system_routine', 'esborrada', 'updated_at']
        );
    }
}
