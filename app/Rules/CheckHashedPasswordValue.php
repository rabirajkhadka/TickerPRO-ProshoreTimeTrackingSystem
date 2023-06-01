<<<<<<< HEAD
=======
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
                $fail("The email address is required.");
            }
            $user = User::getByEmail($this->email)->firstOrFail();
            
            if (Hash::check($value, Arr::get($user, 'password'))) {
                $fail("New password cannot be the same as your previous password. Please choose a different password.");
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
>>>>>>> main
