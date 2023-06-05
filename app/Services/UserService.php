<?php

namespace App\Services;

use App\Enum\UserRoleEnum;
use App\Models\InviteToken;
use App\Models\Role;
use App\Models\UserRole;
use App\Models\User;
use Carbon\Carbon;
use Doctrine\DBAL\Query\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Mockery\Exception;
use App\Http\Resources\UserResource;

class UserService
{
    protected User $userModel;
    protected UserRole $userRoleModel;

    /**
     * @param User $userModel
     * @param UserRole $userRoleModel
     */
    public function __construct(User $userModel, UserRole $userRoleModel)
    {
        $this->userModel = $userModel;
        $this->userRoleModel = $userRoleModel;
    }

    /**
     * @param array $validatedUserRegister
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function saveUserData(array $validatedData)
    {
        try {
            $invitedUser = InviteToken::where('email', Arr::get($validatedData, 'email'))->firstOrFail();
            $user = null;

            DB::transaction(function () use ($validatedData, $invitedUser, &$user) {
                $user = $this->userModel->create($validatedData);
                $user->roles()->attach(
                    Arr::get($invitedUser, 'role_id'),
                    [
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]
                );
                $invitedUser->delete();
            });
            return $user;
        } catch (ModelNotFoundException) {
            throw new ModelNotFoundException();
        } catch (Exception) {
            throw new Exception();
        }
    }

    /**
     * login Service
     * 
     * @param array $validatedUserCreds
     * @return array
     */
    public function login(array $validatedUserCreds)
    {
        $user = $this->getUserWithCreds($validatedUserCreds);
        $token = $user->createToken('auth_token');
        $result = [
            'user' => new UserResource($user->load('roles')),
            'access_token' => $token->plainTextToken,
            'token_type' => 'Bearer',
        ];
        return $result;
    }

    /**
     * Login validation
     * 
     * @param array $validatedUserCreds
     * @return 
     */
    public function getUserWithCreds(array $validatedUserCreds)
    {
        $user = $this->userModel->whereEmail($validatedUserCreds['email'])->firstorfail();

        if (!$user || !Hash::check($validatedUserCreds['password'], $user->password)) {
            throw new Exception('Email address or password is invalid');
        }
        return $user;
    }

    /**
     * @param array $credentials
     * @throws ModelNotFoundException
     * @throws Exception
     * @return object
     */
    public function assignUserRole(array $credentials)
    {
        try {
            $user = $this->userModel->getByEmail(Arr::get($credentials, 'email'))->first();

            $user->roles()->attach(
                Arr::get($credentials, 'role_id'),
                [
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            );
            return $user->roles;
        } catch (ModelNotFoundException $modelNotFoundException) {
            throw new ModelNotFoundException();
        } catch (QueryException $queryException) {
            throw new QueryException();
        } catch (Exception $exception) {
            throw new Exception();
        }
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
     * @param array $validatedForgetPass
     * @return boolean
     */
    public function forgotPassword(array $validatedForgetPass): bool
    {
        $status = Password::sendResetLink($validatedForgetPass);
        return $status === Password::RESET_LINK_SENT ? true : false;
    }

    /**
     * @param array $validatedResetPass
     * @return boolean
     */
    public function resetPassword(array $validatedResetPass): bool
    {
        $status = Password::reset($validatedResetPass, function ($user, $password) {
            $user->forceFill(['password' => $password])->setRememberToken(Str::random(60));
            $user->save();
        });
        return $status === Password::PASSWORD_RESET ? true : false;
    }

    public static function checkUserIdExists($id)
    {
        $user = User::where('id', $id)->first();
        if (!$user) {
            return false;
        }
        return true;
    }

    /**
     * @param integer $id
     * @return boolean
     */
    public function deleteUser(int $id): bool
    {
        $user = $this->userModel->where('id', $id)->firstOrFail();
        if ($this->hasRoleAdmin($user)) return false;
        return $user->delete();
    }

    /**
     * @param object $user
     * @return boolean
     */
    public function hasRoleAdmin(object $user): bool
    {
        return $user->roles->pluck('role')->contains(UserRoleEnum::ADMIN);
    }

    /**
     * logout Service
     * @param $request
     * @return void
     */
    public function logout()
    {
        try {
            /** @var \App\Models\User $user **/
            $user = Auth::user();
            /** @var \Laravel\Sanctum\PersonalAccessToken $token **/
            $token = $user->currentAccessToken();
            $token->delete();
        } catch (Exception) {
            throw new Exception();
        }
    }
}
