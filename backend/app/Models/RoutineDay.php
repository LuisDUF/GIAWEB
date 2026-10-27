<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoutineDay extends Model
{
    public $table = 'routine_days';

       public $timestamps = false;

    public $fillable = [
        'routine_id',
        'name',
        'day_order'
    ];

    protected $casts = [
        'name' => 'string'
    ];

    public static array $rules = [
        'routine_id' => 'required',
        'name' => 'required|string',
        'day_order' => 'required'
    ];

    public function routine(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Routine::class, 'routine_id');
    }

    public function workoutSessions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\WorkoutSession::class, 'routine_day_id');
    }

    public function routineDayExercises(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\RoutineDayExercise::class, 'routine_day_id');
    }
}
