<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBodyLogs extends Model
{
    public $table = 'user_body_logs';

    public $fillable = [
        'user_id',
        'weight_kg',
        'log_date',
        'masa_muscular',
        'body_fat_percentage',
        'abdominal_circumference_cm',
        'hip_circumference_cm'
    ];

    protected $casts = [
        'user_id' => 'string',
        'weight_kg' => 'decimal:0',
        'log_date' => 'datetime',
        'masa_muscular' => 'float',
        'body_fat_percentage' => 'float',
        'abdominal_circumference_cm' => 'float',
        'hip_circumference_cm' => 'float'
    ];

    public static array $rules = [
        'user_id' => 'required|string',
        'weight_kg' => 'required|numeric',
        'log_date' => 'nullable',
        'masa_muscular' => 'nullable|numeric',
        'body_fat_percentage' => 'nullable|numeric',
        'abdominal_circumference_cm' => 'nullable|numeric',
        'hip_circumference_cm' => 'nullable|numeric'
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Profile::class, 'user_id');
    }
}
