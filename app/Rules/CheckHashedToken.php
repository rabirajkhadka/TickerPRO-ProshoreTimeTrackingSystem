<?php

namespace App\Rules;

use App\Models\InviteToken;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class CheckHashedToken implements InvokableRule
{
    protected $email;


    /**
     *
     * @param string|null $email
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
                $fail("Entered email is invalid");
            }
            $invitedUser = InviteToken::where('email', $this->email)->firstOrFail();
            $expired =   Carbon::parse($invitedUser->tokenExpires)->isPast();
            if (!Hash::check($value, Arr::get($invitedUser, 'token')) || $expired) {
                $fail("Please provide a valid token");
            }
        } catch (ModelNotFoundException $modelNotFoundException) {
            Log::error($modelNotFoundException->getMessage());
            $fail("User does not exists");
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            $fail("Something went wrong");
        }
    }
}
