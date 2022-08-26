<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_name',
        'client_id',
        'billable',
        'status',
        'project_color_code',
    ];

    public function client() {
        return $this->belongsTo(Client::class);
    }

    public function timeLogs() {
        return $this->hasMany(TimeLog::class);
    }

    public function users() {
        return $this->belongsToMany(User::class, 'user_projects', 'project_id', 'user_id');
    }
}
