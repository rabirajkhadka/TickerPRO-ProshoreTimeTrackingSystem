<?php

namespace App\Rules;

use App\Models\User;
use Exception;
use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class CheckHashedPasswordValue implements InvokableRule
{

    protected $email;

    /**
     * @param string $email
     */

    public function __construct(string $email)
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
            $user = User::getByEmail($this->email)->firstorFail();
            if (Hash::check($value, $user->password)) {
                $fail("Your new password cannot be the same as your previous password. Please choose a different password.");
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
