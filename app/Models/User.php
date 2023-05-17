<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;


class User extends Model

{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /*
     * Get the role that belongs to the user
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }
     /**
         * Get the number of active admin users.
         */
        /**
         * Undocumented function
         *
         * @return integer
         */
        public function getActiveAdminsCount(): int
        {
            return $this->whereHas('roles', function ($query) {
                $query->where('role', 'admin')->where('activeStatus', true);
            })->count();
        }
    

    /*
     * Get the project that belongs to the user
     */
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'user_projects', 'user_id', 'project_id');
    }

    public function timeLogs(): HasMany
    {
        return $this->hasMany(TimeLog::class, 'user_id');
    }

    public function setPasswordAttribute($password)
    {
        if (trim($password) === '') return;
        $this->attributes['password'] =  Hash::make($password);
    }



 /**
  * This is a PHP function that returns a query builder object filtered by a given email address.
  * 
  * @param Builder query The query parameter is an instance of the Laravel query builder, which allows
  * you to build and execute database queries in a fluent and expressive way.
  * @param string email The email parameter is a string that represents the email address of a user. It
  * is used in the query to retrieve a user from the database based on their email address.
  * 
  * @return `scopeGetByEmail` function is returning a query builder instance filtered by the given
  * email address.
  */
    public function scopeGetByEmail(Builder $query, string $email)
    {
        return $query->where('email', $email);
    }
}
