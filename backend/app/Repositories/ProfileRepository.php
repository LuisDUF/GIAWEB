<?php

namespace App\Repositories;

use App\Models\Profile;
use App\Repositories\BaseRepository;

class ProfileRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'username',
        'gender',
        'height_cm',
        'weight_kg',
        'birth_date',
        'consecutive_log_days',
        'premium'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Profile::class;
    }
}
