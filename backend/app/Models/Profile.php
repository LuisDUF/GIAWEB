<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    public $table = 'profiles';
    public $incrementing = false;
    protected $keyType = 'string';

    public $fillable = [
        'username',
        'gender',
        'height_cm',
        'weight_kg',
        'birth_date',
        'consecutive_log_days',
        'premium',
        'password',
    ];

    protected $casts = [
        'id' => 'string',
        'username' => 'string',
        'gender' => 'string',
        'height_cm' => 'decimal:0',
        'weight_kg' => 'decimal:0',
        'birth_date' => 'date',
        'consecutive_log_days' => 'decimal:0',
        'premium' => 'boolean',
        'password' => 'string',
    ];

    public static array $rules = [
        'username' => 'nullable|string',
        'gender' => 'nullable|string',
        'height_cm' => 'nullable|numeric',
        'weight_kg' => 'nullable|numeric',
        'birth_date' => 'nullable',
        'consecutive_log_days' => 'nullable|numeric',
        'premium' => 'nullable|boolean',
        'created_at' => 'nullable|string|max:50',
        'password' => 'nullable|string|max:255',
    ];

    public function workoutSessions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\WorkoutSession::class, 'user_id');
    }

    public function routines(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Routine::class, 'user_id');
    }

    public function weekDays(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(\App\Models\WeekDay::class, 'profile_rest_days');
    }

public function subscribedRoutines(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
{
    return $this->belongsToMany(
        \App\Models\Routine::class,
        'user_routine_subscriptions', // Tabla pivote
        'user_id',                    // Foreign key en la pivote que apunta a Profile
        'routine_id'                  // Foreign key en la pivote que apunta a Routine
    );
}

    public function workoutTags(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\WorkoutTag::class, 'user_id');
    }

    public function userBodyLogs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\UserBodyLog::class, 'user_id');
    }

        public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
