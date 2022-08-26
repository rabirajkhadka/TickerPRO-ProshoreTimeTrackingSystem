<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_name',
        'client_email',
        'client_number',
        'status',
    ];

    public function projects() {
        return $this->hasMany(Project::class);
    }
}
