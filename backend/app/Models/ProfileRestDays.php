<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileRestDays extends Model
{
    public $table = 'profile_rest_days';

    public $timestamps = false;


    public $fillable = [
        'week_day_id'
    ];

    protected $casts = [
        'profile_id' => 'string'
    ];

    public static array $rules = [
        'week_day_id' => 'required'
    ];

    public function profile(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Profile::class, 'profile_id');
    }

    public function weekDay(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\WeekDay::class, 'week_day_id');
    }
}
