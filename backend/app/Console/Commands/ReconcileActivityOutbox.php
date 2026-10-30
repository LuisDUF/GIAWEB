<?php

namespace App\Console\Commands;

use App\Models\ActivityOutbox;
use App\Services\ActivityOutboxProcessor;
use Illuminate\Console\Command;

class ReconcileActivityOutbox extends Command
{
    protected $signature = 'activity:reconcile {--limit=100 : Maximum pending events to process}';
    protected $description = 'Deliver pending relational audit events to MongoDB idempotently';

    public function handle(ActivityOutboxProcessor $processor): int
    {
        $events = ActivityOutbox::query()
            ->whereNull('processed_at')
            ->where('available_at', '<=', now())
            ->orderBy('id')
            ->limit((int) $this->option('limit'))
            ->get();

        $delivered = 0;
        foreach ($events as $event) {
            $delivered += $processor->process($event) ? 1 : 0;
        }

        $this->info("Delivered {$delivered} of {$events->count()} pending activity events.");
        return self::SUCCESS;
    }
}
