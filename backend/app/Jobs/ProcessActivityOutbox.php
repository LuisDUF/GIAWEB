<?php

namespace App\Jobs;

use App\Models\ActivityOutbox;
use App\Services\ActivityOutboxProcessor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessActivityOutbox implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public readonly int $outboxId) {}

    public function handle(ActivityOutboxProcessor $processor): void
    {
        $event = ActivityOutbox::find($this->outboxId);
        if ($event !== null && !$processor->process($event)) {
            $this->release(60);
        }
    }
}
