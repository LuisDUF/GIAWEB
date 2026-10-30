<?php

namespace App\Services;

use App\Jobs\ProcessActivityOutbox;
use App\Models\ActivityOutbox;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ActivityOutboxService
{
    /** Store the audit event with the relational transaction before Mongo is contacted. */
    public function record(string $action, Model $model, string $entity, array $changes): void
    {
        $event = ActivityOutbox::create([
            'event_id' => (string) Str::uuid(),
            'payload' => [
                'timestamp' => now()->toIso8601String(),
                'user_id' => auth()->id() ?? $model->getAttribute('user_id'),
                'action' => $action,
                'entity' => $entity,
                'entity_id' => (string) $model->getKey(),
                'changes' => $changes,
                'ip_address' => request()?->ip() ?? 'CLI/System',
                'user_agent' => request()?->userAgent() ?? 'CLI/System',
            ],
            'available_at' => now(),
        ]);

        ProcessActivityOutbox::dispatch($event->id)->afterCommit();
    }
}
