<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityOutbox extends Model
{
    protected $table = 'activity_outbox';

    protected $fillable = [
        'event_id', 'payload', 'attempts', 'available_at', 'processed_at', 'last_error',
    ];

    protected $casts = [
        'payload' => 'array',
        'available_at' => 'datetime',
        'processed_at' => 'datetime',
    ];
}
