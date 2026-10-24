<?php

namespace App\Repositories;

use App\Models\RoutineDayExercise;
use App\Repositories\BaseRepository;

class RoutineDayExerciseRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'routine_day_id',
        'exercise_id',
        'order_index',
        'superset_id'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return RoutineDayExercise::class;
    }
}
