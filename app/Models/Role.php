<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function scopeExclude($query, $role)
    {
        return $query->where('role', '!=', $role);
    }
}
