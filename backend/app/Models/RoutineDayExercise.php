<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoutineDayExercise extends Model
{
    public $table = 'routine_day_exercises';

       public $timestamps = false;

    public $fillable = [
        'routine_day_id',
        'exercise_id',
        'order_index',
        'superset_id'
    ];

    protected $casts = [
        'superset_id' => 'string'
    ];

    public static array $rules = [
        'routine_day_id' => 'required',
        'exercise_id' => 'required',
        'order_index' => 'required',
        'superset_id' => 'nullable|string'
    ];

    public function exercise(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Exercise::class, 'exercise_id');
    }

    public function routineDay(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\RoutineDay::class, 'routine_day_id');
    }
}
