<?php

namespace App\Repositories;

use App\Models\WorkoutSession;
use App\Repositories\BaseRepository;

class WorkoutSessionRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'user_id',
        'routine_day_id',
        'date',
        'duration_minutes',
        'notes'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return WorkoutSession::class;
    }
}
