<?php

namespace App\Repositories;

use App\Models\UserBodyLogs;
use App\Repositories\BaseRepository;

class UserBodyLogsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'user_id',
        'weight_kg',
        'log_date',
        'masa_muscular',
        'body_fat_percentage',
        'abdominal_circumference_cm',
        'hip_circumference_cm'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return UserBodyLogs::class;
    }
}
