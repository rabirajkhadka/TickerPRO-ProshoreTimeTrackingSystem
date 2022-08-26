<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['role'];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function scopeExclude($query, $role)
    {
        return $query->where('role', '!=', $role);
    }

    public function users() {
        return $this->belongsToMany(User::class, 'user_roles', 'role_id', 'user_id');
    }

    public function inviteTokens() {
        return $this->hasMany(InviteToken::class);
    }
}
