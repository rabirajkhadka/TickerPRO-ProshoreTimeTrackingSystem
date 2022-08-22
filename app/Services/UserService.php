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
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserLoginRequest;


class UserService
{
    public static function saveUserData(UserStorerequest $request)
    {
        $validated = $request->validated();
        $invitedUser = InviteToken::where('email', $validated['email'])->first();
        $check = Hash::check($validated['token'], $invitedUser->token);

        if (!$check) {
            throw new Exception('Please provide a valid token');
        }

        $result = User::create($validated);
        UserRole::create([
            'user_id' => $result['id'],
            'role_id' => $invitedUser['role_id'],
        ]);
        $invitedUser->delete();
        return $result;
    }

    public static function getUserWithCreds(UserLoginRequest $request)
    {
        $validated = $request->validated();
        $user = User::where('email', $validated['email'])->first();
        if (!$user || !Hash::check($validated['password'], $user->password)) {
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

    public static function forgotPassword($request): bool
    {
        $status = Password::sendResetLink($request->only('email'));
        if ($status === Password::INVALID_USER) return false;
        return true;
    }

    public static function resetPassword(PasswordresetRequest $request): bool
    {
        $validated = $request->safe()->validated();
        $status = Password::reset($validated, function ($user, $password) {
            $user->forceFill(['password' => $password])->setRememberToken(Str::random(60));
            $user->save();
        });
        if ($status === Password::INVALID_TOKEN) return false;
        return true;
    }

    public static function checkUserIdExists($id)
    {
        $user = User::where('id', $id)->first();

        if (!$user) return false;

        return true;
    }
}
