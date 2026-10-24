<?php

namespace App\Repositories;

use App\Models\Exercise;
use App\Repositories\BaseRepository;

class ExerciseRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name',
        'muscle_group_id',
        'description',
        'image_url',
        'animacion',
        'equipment_type',
        'maquina',
        'con_asiento',
        'con_respaldo'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Exercise::class;
    }
}
