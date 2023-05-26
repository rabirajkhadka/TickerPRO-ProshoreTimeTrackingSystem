<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mockery\Exception;

class CheckHashedPasswordValue implements InvokableRule
{
    protected $email;

    /**
     * @param string $email
     */

    public function __construct(string|null $email)
    {
        $this->email = $email;
    }

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        try {
            if (is_null($this->email)) {
                $fail("The given email address is invalid");
            }
            $user = User::getByEmail($this->email)->firstOrFail();
            if (Hash::check($value, Arr::get($user, 'password'))) {
                $fail("Your new password cannot be the same as your previous password. Please choose a different password.");
            }
        } catch (ModelNotFoundException $modelNotFoundException) {
            Log::error($modelNotFoundException->getMessage());
            $fail("Could not reset password. Please check your email address");
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            $fail("An unexpected error occurred. Please try again later.");
        }
    }
}
