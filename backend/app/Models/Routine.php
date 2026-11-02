<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Routine extends Model
{
    public $table = 'routines';
    public $timestamps = false;

    public $fillable = [
        'user_id',
        'name',
        'description',
        'is_public',
        'is_system_routine',
        'esborrada'
    ];

    protected $casts = [
        'user_id' => 'string',
        'name' => 'string',
        'description' => 'string',
        'is_public' => 'boolean',
        'is_system_routine' => 'boolean',
        'esborrada' => 'boolean'
    ];

    public static array $rules = [
        'user_id' => 'nullable|string',
        'name' => 'required|string',
        'description' => 'nullable|string',
        'is_public' => 'nullable|boolean',
        'created_at' => 'nullable',
        'is_system_routine' => 'nullable|boolean',
        'esborrada' => 'nullable|boolean'
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Profile::class, 'user_id');
    }

    public function routineDays(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\RoutineDay::class, 'routine_id');
    }

    public function profile1s(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Profile::class, 'user_routine_subscriptions');
    }
}
