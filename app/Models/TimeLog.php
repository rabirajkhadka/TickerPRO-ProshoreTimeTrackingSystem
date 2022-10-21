<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
Use Carbon\Carbon;

class TimeLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_name',
        'user_id',
        'project_id',
        'billable',
        'start_date',
        'end_date',
        'started_time',
        'ended_time'
    ];
    protected $perPage = 50;

    public function setStartTimeAttribute($value)
    {
        $this->attributes['start_time'] = Carbon::createFromFormat('Y-m-d', $value);
    }

    public function setEndTimeAttribute($value)
    {
        $this->attributes['end_time'] = Carbon::createFromFormat('Y-m-d', $value);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id',);
    }
}
