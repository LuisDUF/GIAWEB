<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\ResetsPrimaryKeySequence;
use Illuminate\Database\Seeder;
use App\Models\MuscleGroup;
use Illuminate\Support\Facades\DB;

class MuscleGroupSeeder extends Seeder
{
    use ResetsPrimaryKeySequence;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = database_path('seeders/data/muscle_groups.csv');

        if (!file_exists($filePath)) {
            $this->command->error("El archivo CSV no existe en la ruta: {$filePath}");
            return;
        }

        $file = fopen($filePath, 'r');

        // Leer la línea de encabezados (id, name)
        $headers = fgetcsv($file, 1000, ',');

        $groups = [];

        while (($data = fgetcsv($file, 1000, ',')) !== FALSE) {
            $row = array_combine($headers, $data);

            $groups[] = [
                'id'         => (int) $row['id'],
                'name'       => trim($row['name']),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        fclose($file);

        if (!empty($groups)) {
            // Usamos upsert para insertar o actualizar en caso de que ya existan los IDs
            MuscleGroup::upsert(
                $groups,
                ['id'], // Columna de conflicto
                ['name', 'updated_at'] // Columnas a actualizar si ya existe
            );

            // Ajustar la secuencia de Postgres para que no falle el autoincremento de IDs posteriores
            $maxId = MuscleGroup::max('id') ?? 1;
            $this->resetPrimaryKeySequence('muscle_groups', 'muscle_groups_id_seq', $maxId);

            $this->command->info('¡Grupos musculares cargados exitosamente desde el CSV!');
        }
    }
}
