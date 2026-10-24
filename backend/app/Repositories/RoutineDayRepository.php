<?php

namespace App\Repositories;

use App\Models\RoutineDay;
use App\Repositories\BaseRepository;

class RoutineDayRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'routine_id',
        'name',
        'day_order'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return RoutineDay::class;
    }
}
