<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRoutineSubscription extends Model
{
    public $table = 'user_routine_subscriptions';

    public $fillable = [
        'routine_id',
        'saved_at',
        'is_active'
    ];

    protected $casts = [
        'user_id' => 'string',
        'saved_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    public static array $rules = [
        'routine_id' => 'required',
        'saved_at' => 'nullable',
        'is_active' => 'nullable|boolean'
    ];

    public function routine(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Routine::class, 'routine_id');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Profile::class, 'user_id');
    }
}
