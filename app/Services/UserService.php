<?php

namespace App\Services;
use App\Models\InviteToken;
use App\Models\Role;
use App\Models\UserRole;
use App\Models\User;
use Carbon\Carbon;
use Doctrine\DBAL\Query\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Mockery\Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
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
     *
     * @param array $credentials
     * 
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


    /**
 * Update the status of a user.
 *
 * @param int $userId The ID of the user to update.
 * @param int $authenticatedUserId The ID of the authenticated user.
 */
public function updateUserStatus($userId) 
{
    try {
        $user = User::findOrFail($userId);

        if(!$user){
            return response()->json([
                'message' => 'User doesnot exists'
            ], Response::HTTP_NOT_FOUND);
        }

        // Check if the user is an admin
        $isAdmin = $user->roles()->pluck('role')->contains('admin');
        
        if ($isAdmin) {
            // Check if the user is trying to disable itself
            if ($user->id === auth()->user()->id) {
                return response()->json([
                    'message' => 'Admin cannot disable itself'
                ], Response::HTTP_FORBIDDEN);
                // $result = 'Admin cannot disable itself';
                // return $result;
            }

            // Check if disabling admin user will result in no active admin users
            $activeAdminsCount = $user->getActiveAdminsCount();

            if ($activeAdminsCount <= 1) {
                return response()->json([
                    'message' => 'At least one admin user must be active'
                ], Response::HTTP_FORBIDDEN);
            }
        }

        // Update the user status
        if(!$user->activeStatus){
            $user->activeStatus = true;
        }else{
            $user->activeStatus = false;
        }
        $user->save();

        $result = [
            'status' => Response::HTTP_OK,
            'message' => 'User status updated'
        ];
    } catch (Exception $e) {
        Log::error($e->getMessage());
        $result = [
            'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            'error' => $e->getMessage()
        ];
    }

    return $result;
    }

}