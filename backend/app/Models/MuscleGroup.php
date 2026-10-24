<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MuscleGroup extends Model
{
    public $table = 'muscle_groups';

    public $fillable = [
        'name'
    ];

    protected $casts = [
        'name' => 'string'
    ];

    public static array $rules = [
        'name' => 'required|string'
    ];

    public function exercises(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Exercise::class, 'muscle_group_id');
    }
}
