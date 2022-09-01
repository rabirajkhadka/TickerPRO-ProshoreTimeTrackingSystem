<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_roles', 'role_id', 'user_id');
    }

    public function inviteTokens(): HasMany 
    {
        return $this->hasMany(InviteToken::class, 'role_id');
    }
}
