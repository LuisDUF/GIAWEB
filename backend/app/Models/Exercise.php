<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    public $table = 'exercises';
    public $timestamps = false;

    public $fillable = [
        'name',
        'muscle_group_id',
        'description',
        'image_url',
        'animacion',
        'equipment_type',
        'maquina',
        'con_asiento',
        'con_respaldo'
    ];

    protected $casts = [
        'name' => 'string',
        'description' => 'string',
        'image_url' => 'string',
        'animacion' => 'string',
        'equipment_type' => 'string',
        'maquina' => 'string',
        'con_asiento' => 'boolean',
        'con_respaldo' => 'boolean'
    ];

    public static array $rules = [
        'name' => 'required|string',
        'muscle_group_id' => 'required',
        'description' => 'nullable|string',
        'image_url' => 'nullable|string',
        'animacion' => 'nullable|string',
        'equipment_type' => 'nullable|string',
        'maquina' => 'nullable|string',
        'con_asiento' => 'nullable|boolean',
        'con_respaldo' => 'nullable|boolean'
    ];

    public function muscleGroup(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\MuscleGroup::class, 'muscle_group_id');
    }

    public function routineDayExercises(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\RoutineDayExercise::class, 'exercise_id');
    }

    public function sessionExercises(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\SessionExercise::class, 'exercise_id');
    }
}
