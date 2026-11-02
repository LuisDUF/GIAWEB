<?php

namespace Database\Seeders\Concerns;

use Carbon\CarbonImmutable;

trait NormalizesCsvTimestamps
{
    /** Convierte fechas CSV con zona horaria o milisegundos al formato DATETIME de MySQL. */
    protected function csvTimestamp(?string $value): string
    {
        return blank($value)
            ? now()->utc()->format('Y-m-d H:i:s')
            : CarbonImmutable::parse($value)->utc()->format('Y-m-d H:i:s');
    }
}
