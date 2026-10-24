<?php

namespace App\Repositories;

use App\Models\SessionSets;
use App\Repositories\BaseRepository;

class SessionSetsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'session_exercise_id',
        'set_number',
        'weight_kg',
        'reps',
        'rpe',
        'set_type',
        'sub_order'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return SessionSets::class;
    }
}
