<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = database_path('seeders/data/users.csv');

        if (!file_exists($filePath)) {
            $this->command->error("El archivo CSV no existe en la ruta: {$filePath}");
            return;
        }

        $file = fopen($filePath, 'r');

        // Leer la línea de encabezados
        $headers = fgetcsv($file, 1000, ',');

        $users = [];
        $batchSize = 50;
        $defaultPasswordHash = Hash::make('password123'); // Contraseña genérica por defecto

        while (($data = fgetcsv($file, 1000, ',')) !== FALSE) {
            $row = array_combine($headers, $data);

            $users[] = [
                'id'                => (int) $row['id'],
                'name'              => trim($row['name']),
                'email'             => trim($row['email']),
                'email_verified_at' => !empty($row['email_verified_at']) ? $row['email_verified_at'] : null,
                'password'          => !empty($row['password']) ? $row['password'] : $defaultPasswordHash,
                'is_admin'          => filter_var($row['is_admin'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'created_at'        => !empty($row['created_at']) ? $row['created_at'] : now(),
                'updated_at'        => !empty($row['updated_at']) ? $row['updated_at'] : now(),
            ];

            if (count($users) >= $batchSize) {
                $this->upsertUsers($users);
                $users = [];
            }
        }

        if (!empty($users)) {
            $this->upsertUsers($users);
        }

        fclose($file);

        // Ajustar la secuencia de autoincremento de PostgreSQL
        $maxId = User::max('id') ?? 1;
        DB::statement("SELECT setval('users_id_seq', {$maxId});");

        $this->command->info('¡Usuarios cargados exitosamente desde el CSV!');
    }

    /**
     * Método auxiliar para realizar inserción/actualización masiva (upsert)
     */
    private function upsertUsers(array $users): void
    {
        User::upsert(
            $users,
            ['id'], // Columna en conflicto (PK)
            [
                'name',
                'email',
                'email_verified_at',
                'password',
                'is_admin',
                'updated_at'
            ]
        );
    }
}
