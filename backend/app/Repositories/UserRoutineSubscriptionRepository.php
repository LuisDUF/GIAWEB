<?php

namespace App\Repositories;

use App\Models\UserRoutineSubscription;
use App\Repositories\BaseRepository;

class UserRoutineSubscriptionRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'routine_id',
        'saved_at',
        'is_active'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return UserRoutineSubscription::class;
    }
}
