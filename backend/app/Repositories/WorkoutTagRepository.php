<?php

namespace App\Repositories;

use App\Models\WorkoutTag;
use App\Repositories\BaseRepository;

class WorkoutTagRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'user_id',
        'name'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return WorkoutTag::class;
    }
}
