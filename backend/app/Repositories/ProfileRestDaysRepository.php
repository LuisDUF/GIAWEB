<?php

namespace App\Repositories;

use App\Models\ProfileRestDays;
use App\Repositories\BaseRepository;

class ProfileRestDaysRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'week_day_id'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return ProfileRestDays::class;
    }
}
