<?php

namespace App\Repositories;

use App\Models\WeekDays;
use App\Repositories\BaseRepository;

class WeekDaysRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'day'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return WeekDays::class;
    }
}
