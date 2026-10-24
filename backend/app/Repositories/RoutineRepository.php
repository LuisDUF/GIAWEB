<?php

namespace App\Repositories;

use App\Models\Routine;
use App\Repositories\BaseRepository;

class RoutineRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'user_id',
        'name',
        'description',
        'is_public',
        'is_system_routine',
        'esborrada'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Routine::class;
    }
}
