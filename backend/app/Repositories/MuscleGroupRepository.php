<?php

namespace App\Repositories;

use App\Models\MuscleGroup;
use App\Repositories\BaseRepository;

class MuscleGroupRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return MuscleGroup::class;
    }
}
