<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\ActivityOutbox;
use Illuminate\Support\Facades\Log;

class ActivityOutboxProcessor
{
    public function process(ActivityOutbox $event): bool
    {
        if ($event->processed_at !== null) {
            return true;
        }

        try {
            // event_id makes delivery idempotent when a worker retries after a network failure.
            ActivityLog::firstOrCreate(['event_id' => $event->event_id], $event->payload);
            $event->forceFill(['processed_at' => now(), 'last_error' => null])->save();
            return true;
        } catch (\Throwable $exception) {
            $event->increment('attempts');
            $event->forceFill([
                'last_error' => substr($exception->getMessage(), 0, 1000),
                'available_at' => now()->addMinutes(min(30, max(1, $event->attempts * 2))),
            ])->save();
            Log::warning('Activity outbox delivery failed; it will be reconciled.', ['event_id' => $event->event_id]);
            return false;
        }
    }
}
