<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeekDays extends Model
{
    public $table = 'week_days';

    public $fillable = [
        'day'
    ];

    protected $casts = [
        'day' => 'string'
    ];

    public static array $rules = [
        'day' => 'required|string'
    ];

    public function profiles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Profile::class, 'profile_rest_days');
    }
}
