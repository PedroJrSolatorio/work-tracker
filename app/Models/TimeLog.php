<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeLog extends Model
{
    protected $fillable = [
        'work_session_id',
        'start_time',
        'end_time',
        'duration_minutes'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime'
    ];

    public function workSession()
    {
        return $this->belongsTo(WorkSession::class);
    }
}
