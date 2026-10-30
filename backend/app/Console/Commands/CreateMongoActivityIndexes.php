<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateMongoActivityIndexes extends Command
{
    protected $signature = 'activity:mongo-indexes';
    protected $description = 'Create the idempotency and query indexes for MongoDB activity logs';

    public function handle(): int
    {
        $collection = DB::connection('mongodb')->getMongoDB()->selectCollection('activity_logs');
        $collection->createIndex(['event_id' => 1], ['unique' => true, 'name' => 'activity_event_id_unique']);
        $collection->createIndex(['timestamp' => -1], ['name' => 'activity_timestamp_desc']);
        $collection->createIndex(['entity' => 1, 'entity_id' => 1], ['name' => 'activity_entity_lookup']);
        $this->info('MongoDB activity log indexes are present.');
        return self::SUCCESS;
    }
}
