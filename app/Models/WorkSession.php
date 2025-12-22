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

    public function getRemainingMinutesAttribute()
    {
        return max(0, $this->target_minutes - $this->worked_minutes);
    }

    public function getProgressPercentageAttribute()
    {
        return $this->target_minutes > 0
            ? min(100, round(($this->worked_minutes / $this->target_minutes) * 100, 2))
            : 0;
    }
}
