<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as MongoModel;

class ActivityLog extends MongoModel
{
    protected $connection = 'mongodb';
    protected $collection = 'activity_logs';

    protected $fillable = [
        'timestamp',
        'user_id',
        'action',
        'entity',
        'entity_id',
        'changes',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
        'changes' => 'array',
    ];
}
