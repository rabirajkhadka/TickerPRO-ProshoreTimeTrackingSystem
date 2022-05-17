<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_name',
        'user_id',
        'project_id',
        'billable',
        'start_time',
        'end_time',
    ];
}
