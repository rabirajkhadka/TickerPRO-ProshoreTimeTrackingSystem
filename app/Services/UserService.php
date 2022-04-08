<?php

namespace App\Services;

use App\Models\UserRole;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use http\Exception\InvalidArgumentException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;

class UserService
{
    /*
        * Validate User Data
        * Store to the database if there is no errors
    */
    public static function saveUserData($request)
    {
        $validated = $request->validated();
        // add the user role
        $result = User::create($validated);
        $roles = UserRole::create([
            'user_id' => $result['id'],
            'role_id' => 1,
        ]);
        return $result;
    }

    /*
     * Validate the obatined email and password
     * Check if the user exists or not
     * If User exists then compare the password with stored hash
     * Return User details if true else throw exception
     */
    public static function getUserWithCreds($request)
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

    public static function deleteRoles($id)
    {
        $roles = UserRole::all()->where('user_id', $id);
        foreach ($roles as $role) {
            $role->delete();
        }
    }

    public static function resetPassword($request): bool
    {
        $status = Password::sendResetLink($request->only('email'));
        if ($status === Password::INVALID_USER) return false;
        return true;
    }
}
