<?php

namespace App\Rules;

use App\Models\PasswordReset;
use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Exception;

class VerfiyResetToken implements InvokableRule
{
    protected  $email;

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
                $fail("The email address is required.");
            }    
            $user = PasswordReset::getByEmail($this->email)->firstOrFail();
            $expired = $user->created_at->addMinutes(30)->isPast();

            if (!Hash::check($value, $user->token) || $expired) {
                $fail("The entered token is invalid.");
            }
        } catch (ModelNotFoundException $modelNotFoundException) {
            Log::error($modelNotFoundException->getMessage());
            $fail("User does not exist.");
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            $fail("Something went wrong. Please try again later.");
        }
    }
}
