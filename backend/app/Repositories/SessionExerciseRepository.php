<?php

namespace App\Repositories;

use App\Models\SessionExercise;
use App\Repositories\BaseRepository;

class SessionExerciseRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'session_id',
        'exercise_id',
        'total_sets',
        'max_weight_lifted',
        'min_weight_lifted',
        'is_skipped',
        'is_adhoc',
        'superset_id',
        'notas'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return SessionExercise::class;
    }
}
