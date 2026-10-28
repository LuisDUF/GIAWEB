<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserRoutineSubscription;
use Illuminate\Support\Facades\DB;

class UserRoutineSubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = database_path('seeders/data/user_routine_subscriptions.csv');

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

        $subscriptions = [];
        $batchSize = 50;
        $autoIncrementId = 1;

        while (($data = fgetcsv($file, 1000, ',')) !== FALSE) {
            if (count($data) !== count($headers)) {
                continue;
            }

            $row = array_combine($headers, $data);

            $id = isset($row['id']) && is_numeric($row['id']) ? (int) $row['id'] : $autoIncrementId++;

            // Obtener el UUID del perfil (ya sea que la columna en el CSV se llame profile_id o user_id)
            $userId = !empty($row['user_id']) ? trim($row['user_id']) : (!empty($row['profile_id']) ? trim($row['profile_id']) : null);

            $subscriptions[] = [
                'id'         => $id,
                'user_id'    => $userId,
                'routine_id' => (int) ($row['routine_id'] ?? 0),
                'saved_at'   => !empty($row['saved_at']) ? $row['saved_at'] : now(),
                'is_active'  => filter_var($row['is_active'] ?? true, FILTER_VALIDATE_BOOLEAN),
                'created_at' => !empty($row['created_at']) ? $row['created_at'] : now(),
                'updated_at' => !empty($row['updated_at']) ? $row['updated_at'] : now(),
            ];

            if (count($subscriptions) >= $batchSize) {
                $this->upsertSubscriptions($subscriptions);
                $subscriptions = [];
            }
        }

        if (!empty($subscriptions)) {
            $this->upsertSubscriptions($subscriptions);
        }

        fclose($file);

        $maxId = UserRoutineSubscription::max('id') ?? 1;
        DB::statement("SELECT setval('user_routine_subscriptions_id_seq', {$maxId});");

        $this->command->info('¡Suscripciones a rutinas cargadas exitosamente!');
    }

    private function upsertSubscriptions(array $subscriptions): void
    {
        UserRoutineSubscription::upsert(
            $subscriptions,
            ['id'],
            ['user_id', 'routine_id', 'saved_at', 'is_active', 'updated_at']
        );
    }
}
