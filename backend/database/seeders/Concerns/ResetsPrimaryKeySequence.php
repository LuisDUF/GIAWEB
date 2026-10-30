<?php

namespace Database\Seeders\Concerns;

use Illuminate\Support\Facades\DB;

trait ResetsPrimaryKeySequence
{
    /** Ajusta el siguiente ID tras importar claves explícitas. */
    protected function resetPrimaryKeySequence(string $table, string $sequence, int $maxId): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("SELECT setval('{$sequence}', {$maxId});");

            return;
        }

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `{$table}` AUTO_INCREMENT = ".($maxId + 1));
        }
    }
}
