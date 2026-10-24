<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkoutTag extends Model
{
    public $table = 'workout_tags';

    public $fillable = [
        'user_id',
        'name'
    ];

    protected $casts = [
        'user_id' => 'string',
        'name' => 'string'
    ];

    public static array $rules = [
        'user_id' => 'required|string',
        'name' => 'required|string',
        'created_at' => 'required',
        'updated_at' => 'required'
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Profile::class, 'user_id');
    }

    public function workoutSessions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(\App\Models\WorkoutSession::class, 'workout_session_tags');
    }
}
