<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class CheckUserRoleExistsRule implements Rule
{
    protected $email, $roleId;


    /**
     * Undocumented function
     *
     * @param string $email
     * @param string $roleId
     */
    public function __construct(string $email, string $roleId)
    {
        $this->email = $email;
        $this->roleId = $roleId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */

    public function passes($attribute, $value)
    {
        $user = User::getByEmail($this->email)->first();
        if (!$user) {
            return false;
        }
        $existingRoles = $user->roles->pluck('id');
        return !$existingRoles->contains($this->roleId);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */

    public function message()
    {
        return 'User is already assigned this role.';
    }
}
