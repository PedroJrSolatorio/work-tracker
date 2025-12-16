<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkSession extends Model
{
    protected $fillable = [
        'date',
        'target_minutes',
        'worked_minutes',
        'status',
        'current_start_time'
    ];

    protected $casts = [
        'date' => 'date',
        'current_start_time' => 'datetime'
    ];

    public function timeLogs()
    {
        return $this->hasMany(TimeLog::class);
    }
}
