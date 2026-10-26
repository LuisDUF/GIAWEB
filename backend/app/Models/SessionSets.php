<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SessionSets extends Model
{
    public $table = 'session_sets';
public $timestamps = false;
    public $fillable = [
        'session_exercise_id',
        'set_number',
        'weight_kg',
        'reps',
        'rpe',
        'set_type',
        'sub_order'
    ];

    protected $casts = [
        'weight_kg' => 'decimal:0',
        'set_type' => 'string'
    ];

    public static array $rules = [
        'session_exercise_id' => 'required',
        'set_number' => 'required',
        'weight_kg' => 'required|numeric',
        'reps' => 'required',
        'rpe' => 'nullable',
        'set_type' => 'nullable|string',
        'sub_order' => 'nullable'
    ];

    public function sessionExercise(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\SessionExercise::class, 'session_exercise_id');
    }
}
