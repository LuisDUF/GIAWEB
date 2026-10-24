<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkoutSessionTag extends Model
{
    public $table = 'workout_session_tags';

    public $fillable = [
        'user_id',
        'tag_id'
    ];

    protected $casts = [
        'user_id' => 'string'
    ];

    public static array $rules = [
        'user_id' => 'required|string',
        'tag_id' => 'required',
        'created_at' => 'required'
    ];

    public function session(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\WorkoutSession::class, 'session_id');
    }

    public function tag(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\WorkoutTag::class, 'tag_id');
    }
}
