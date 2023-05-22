<?php

namespace App\Rules;

use App\Models\PasswordReset;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class VerifyResetToken implements InvokableRule
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
     * @throws ModelNotFoundException
     * @throws Exception
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */

    public function __invoke($attribute, $value, $fail)
    {
        try {
            if (is_null($this->email)) {
                $fail("Entered email is invalid");
            }
            $user = PasswordReset::getByEmail($this->email)->firstOrFail();
            $expired = Carbon::parse(Arr::get($user, 'created_at'))->addMinutes(30)->isPast();

            if (!Hash::check($value, Arr::get($user, 'token')) || $expired) {
                $fail("The entered token is Invalid or Expired");
            }
        } catch (ModelNotFoundException $modelNotFoundException) {
            Log::error($modelNotFoundException->getMessage());
            $fail("Could not reset password. Please check your token or email address");
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            $fail("Something went wrong");
        }
    }
}
