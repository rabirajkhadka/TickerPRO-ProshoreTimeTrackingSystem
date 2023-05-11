<?php

namespace App\Services;

use App\Http\Resources\UserRoleResource;
use App\Models\InviteToken;
use App\Models\Role;
use App\Models\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Mockery\Exception;


class UserService
{
    protected User $userModel;
    protected UserRole $userRoleModel;

    /**
     *
     * @param User $userModel
     * @param UserRole $userRoleModel
     */

    public function __construct(User $userModel, UserRole $userRoleModel)
    {
        $this->userModel = $userModel;
        $this->userRoleModel = $userRoleModel;
    }

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




    /**
     *
     * @param array $cred
     */

    public function assignUserRole(array $cred)
    {
        $user = $this->userModel->getByEmail($cred['email'])->first();

        if ($user === null)
            throw new ModelNotFoundException("Email not found");

        $role = $this->userRoleModel->create([
            'user_id' => Arr::get($user, 'id'),
            'role_id' => Arr::get($cred, 'role_id')
        ]);

        if (!$role)
            throw new Exception("User could not be assigned role");

        return $role;
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
