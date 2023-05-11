<?php

namespace App\Rules;

use App\Models\User;
use Exception;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Log;

class CheckUserRoleExistsRule implements Rule
{
    protected string|null $email;


    /**
     *
     * @param string|null $email
     */
    public function __construct(?string $email)
    {
        $this->email = $email;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */

    public function passes($attribute, $value): bool
    {
        try {
            $user = User::getByEmail($this->email)->firstorFail();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
        $existingRoles = $user->roles->pluck('id');
        return !$existingRoles->contains($value);
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
