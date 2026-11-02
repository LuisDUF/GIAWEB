<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SessionExercise extends Model
{
    public $table = 'session_exercises';
public $timestamps = false;
    public $fillable = [
        'session_id',
        'exercise_id',
        'total_sets',
        'max_weight_lifted',
        'min_weight_lifted',
        'is_skipped',
        'is_adhoc',
        'superset_id',
        'notas'
    ];

    protected $casts = [
        'max_weight_lifted' => 'decimal:0',
        'min_weight_lifted' => 'decimal:0',
        'is_skipped' => 'boolean',
        'is_adhoc' => 'boolean',
        'superset_id' => 'string',
        'notas' => 'string'
    ];

    public static array $rules = [
        'session_id' => 'required',
        'exercise_id' => 'required',
        'total_sets' => 'nullable',
        'max_weight_lifted' => 'nullable|numeric',
        'min_weight_lifted' => 'nullable|numeric',
        'is_skipped' => 'nullable|boolean',
        'is_adhoc' => 'nullable|boolean',
        'superset_id' => 'nullable|string',
        'notas' => 'nullable|string'
    ];

    public function exercise(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Exercise::class, 'exercise_id');
    }

    public function session(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\WorkoutSession::class, 'session_id');
    }

    public function sessionSets(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\SessionSets::class, 'session_exercise_id');
    }
}
