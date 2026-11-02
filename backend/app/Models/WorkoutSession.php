<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkoutSession extends Model
{
    public $table = 'workout_sessions';
public $timestamps = false;
    public $fillable = [
        'user_id',
        'routine_day_id',
        'date',
        'duration_minutes',
        'notes'
    ];

    protected $casts = [
        'user_id' => 'string',
        'date' => 'datetime',
        'notes' => 'string'
    ];

    public static array $rules = [
        'user_id' => 'required|string',
        'routine_day_id' => 'nullable',
        'date' => 'required',
        'duration_minutes' => 'nullable',
        'notes' => 'nullable|string'
    ];

    public function routineDay(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\RoutineDay::class, 'routine_day_id');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Profile::class, 'user_id');
    }

    public function workoutTags(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(\App\Models\WorkoutTag::class, 'workout_session_tags');
    }

    public function sessionExercises(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\SessionExercise::class, 'session_id');
    }
}
