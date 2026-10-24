<?php

namespace App\Repositories;

use App\Models\WorkoutSessionTag;
use App\Repositories\BaseRepository;

class WorkoutSessionTagRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'user_id',
        'tag_id'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return WorkoutSessionTag::class;
    }
}
