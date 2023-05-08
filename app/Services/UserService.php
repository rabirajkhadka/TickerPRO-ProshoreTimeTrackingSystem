<?php

namespace App\Services;


use App\Models\InviteToken;
use App\Models\Role;
use App\Models\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
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

    public static function getUser($cred)
    {
        $user = User::where('email', $cred['email'])->first();
        $existingRoles = UserRole::where('user_id', $user->id)->pluck('role_id');

        if ($existingRoles->contains($cred['role_id'])) {
            throw new Exception('User is already assigned this role');
        }
        if (!$user) {
            throw new Exception('User does not exist');
        }
        return $user;
    }

    public static function roles()
    {
        return Role::exclude('admin')->get();
    }

    public static function forgotPassword($validatedForgetPass): bool
    {
        $status = Password::sendResetLink($validatedForgetPass);
        if ($status === Password::INVALID_USER) return false;
        return true;
    }

    public static function resetPassword(array $validatedResetPass): bool
    {
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
