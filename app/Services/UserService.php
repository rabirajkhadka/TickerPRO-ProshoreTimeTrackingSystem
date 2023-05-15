<?php

namespace App\Services;

use App\Mail\InviteCreated;
use App\Models\InviteToken;
use App\Models\Role;
use App\Models\UserRole;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use http\Exception\InvalidArgumentException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Mockery\Exception;


class UserService
{
    public static function saveUserData(array $validatedUserRegister)
    {
        $invitedUser = InviteToken::where('email', $validatedUserRegister['email'])->first();
        $check = Hash::check($validatedUserRegister['token'], $invitedUser->token);

        if (!$check) {
            throw new Exception('Please provide a valid token');
        }

        $result = User::create($validatedUserRegister);
        UserRole::create([
            'user_id' => $result['id'],
            'role_id' => $invitedUser['role_id'],
        ]);
        $invitedUser->delete();
        return $result;
    }

    public static function getUserWithCreds(array $validatedUserCreds)
    {

        $user = User::where('email', $validatedUserCreds['email'])->first();
        if (!$user || !Hash::check($validatedUserCreds['password'], $user->password)) {
            throw new Exception('Email address or password is invalid');
        }
        return $user;
    }

    public static function getUser($cred, $rules)
    {
        $validateReq = validator($cred, $rules);
        if ($validateReq->fails()) {
            throw new Exception($validateReq->errors());
        }
        $user = User::where('email', $cred['email'])->first();
        if (!$user) {
            throw new Exception('User does not exist');
        }
        return $user;
    }

    public static function roles()
    {
        return Role::exclude('admin')->get();
    }

    /**
     *
     * @param array $validatedForgetPass
     * @return boolean
     */
    public static function forgotPassword(array $validatedForgetPass): bool
    {
        $status = Password::sendResetLink($validatedForgetPass);
        if ($status === Password::INVALID_USER) return false;
        return true;
    }

    /**
     *
     * @param array $validatedResetPass
     * @return boolean
     */
    public static function resetPassword(array $validatedResetPass): bool
    {
        $user = User::where('email', $validatedResetPass['email'])->first();

        if (Hash::check($validatedResetPass['password'], $user->password)) {
            return false;
        }

        $status = Password::reset($validatedResetPass, function ($user, $password) {
            $user->forceFill(['password' => $password])->setRememberToken(Str::random(60));
            $user->save();
        });
        if ($status === Password::INVALID_TOKEN) return false;
        return true;
    }

    public static function checkUserIdExists($id)
    {
        $user = User::where('id', $id)->first();

        if (!$user) {
            return false;
        }

        return true;
    }
}
