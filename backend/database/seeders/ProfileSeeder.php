<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profile;

class ProfileSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = database_path('seeders/data/profiles.csv');

        if (!file_exists($filePath)) {
            $this->command->error("El archivo CSV no existe en la ruta: {$filePath}");
            return;
        }

        $file = fopen($filePath, 'r');
        $headers = fgetcsv($file, 1000, ',');

        $profiles = [];
        $batchSize = 50;

        while (($data = fgetcsv($file, 1000, ',')) !== FALSE) {
            $row = array_combine($headers, $data);

            $profiles[] = [
                'id'                   => trim($row['id']), // MANTENER COMO STRING / UUID
                'username'             => trim($row['username']),
                'gender'               => !empty($row['gender']) ? trim($row['gender']) : null,
                'height_cm'            => !empty($row['height_cm']) ? (float) $row['height_cm'] : null,
                'weight_kg'            => !empty($row['weight_kg']) ? (float) $row['weight_kg'] : null,
                'birth_date'           => !empty($row['birth_date']) ? $row['birth_date'] : null,
                'consecutive_log_days' => !empty($row['consecutive_log_days']) ? (int) $row['consecutive_log_days'] : 0,
                'premium'              => filter_var($row['premium'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'esadmin'              => filter_var($row['esadmin'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'user_id'              => (int) $row['user_id'], // Entero autoincremental de la tabla users
                'created_at'           => !empty($row['created_at']) ? $row['created_at'] : now(),
                'updated_at'           => now(),
            ];

            if (count($profiles) >= $batchSize) {
                $this->upsertProfiles($profiles);
                $profiles = [];
            }
        }

        if (!empty($profiles)) {
            $this->upsertProfiles($profiles);
        }

        fclose($file);

        // NOTA: No se ejecuta SELECT setval() porque 'id' es un UUID, no una secuencia.

        $this->command->info('¡Perfiles cargados exitosamente desde el CSV!');
    }

    private function upsertProfiles(array $profiles): void
    {
        Profile::upsert(
            $profiles,
            ['id'],
            [
                'username',
                'gender',
                'height_cm',
                'weight_kg',
                'birth_date',
                'consecutive_log_days',
                'premium',
                'esadmin',
                'user_id',
                'updated_at'
            ]
        );
    }
}
