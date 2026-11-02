<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\ResetsPrimaryKeySequence;
use Illuminate\Database\Seeder;
use App\Models\RoutineDay;
use Illuminate\Support\Facades\DB;

class RoutineDaySeeder extends Seeder
{
    use ResetsPrimaryKeySequence;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = database_path('seeders/data/routine_days.csv');

        if (!file_exists($filePath)) {
            $this->command->error("El archivo CSV no existe en la ruta: {$filePath}");
            return;
        }

        $file = fopen($filePath, 'r');

        // Leer la línea de encabezados
        $headers = fgetcsv($file, 1000, ',');

        $days = [];
        $batchSize = 50;

        while (($data = fgetcsv($file, 1000, ',')) !== FALSE) {
            $row = array_combine($headers, $data);

            $days[] = [
                'id'         => (int) $row['id'],
                'routine_id' => (int) $row['routine_id'],
                'name'       => trim($row['name']),
                'day_order'  => (int) $row['day_order'],
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($days) >= $batchSize) {
                $this->upsertDays($days);
                $days = [];
            }
        }

        if (!empty($days)) {
            $this->upsertDays($days);
        }

        fclose($file);

        // Ajustar la secuencia de PostgreSQL para evitar colisiones de autoincremento en el id
        $maxId = RoutineDay::max('id') ?? 1;
        $this->resetPrimaryKeySequence('routine_days', 'routine_days_id_seq', $maxId);

        $this->command->info('¡Días de rutina cargados exitosamente desde el CSV!');
    }

    /**
     * Método auxiliar para realizar inserción/actualización masiva (upsert)
     */
    private function upsertDays(array $days): void
    {
        RoutineDay::upsert(
            $days,
            ['id'], // Columna en conflicto (PK)
            [
                'routine_id',
                'name',
                'day_order',
                'updated_at'
            ]
        );
    }
}
