<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;

    protected $table = "password_resets";
    protected $primaryKey = "email";
    protected $guarded = [];


    /**
     *
     * @param Builder $query
     * @param string $email
     * @return void
     */

    public function scopeGetByEmail(Builder $query, string $email)
    {
        return $query->where('email', $email);
    }
}
