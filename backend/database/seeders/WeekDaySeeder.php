<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\ResetsPrimaryKeySequence;
use Illuminate\Database\Seeder;
use App\Models\WeekDays;
use Illuminate\Support\Facades\DB;

class WeekDaySeeder extends Seeder
{
    use ResetsPrimaryKeySequence;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = database_path('seeders/data/week_days.csv');

        if (!file_exists($filePath)) {
            $this->command->error("El archivo CSV no existe en la ruta: {$filePath}");
            return;
        }

        $file = fopen($filePath, 'r');

        // Leer la línea de encabezados
        $headers = fgetcsv($file, 1000, ',');

        $days = [];

        while (($data = fgetcsv($file, 1000, ',')) !== FALSE) {
            $row = array_combine($headers, $data);

            $days[] = [
                'id'         => (int) $row['id'],
                'day'        => trim($row['day']),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        fclose($file);

        if (!empty($days)) {
            // Realizar upsert sobre los 7 días de la semana
            WeekDays::upsert(
                $days,
                ['id'], // Columna en conflicto (PK)
                ['day', 'updated_at']
            );

            // Ajustar el siguiente ID para el motor relacional configurado.
            $maxId = WeekDays::max('id') ?? 1;
            $this->resetPrimaryKeySequence('week_days', 'week_days_id_seq', $maxId);

            $this->command->info('¡Días de la semana cargados exitosamente desde el CSV!');
        }
    }
}
