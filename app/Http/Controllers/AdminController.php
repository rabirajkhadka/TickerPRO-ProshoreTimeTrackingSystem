<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignRoleRequest;
use App\Http\Requests\MemberInviteRequest;
use App\Services\InviteService;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Models\User;
use Mockery\Exception;
use App\Http\Resources\AdminResource;
use App\Http\Resources\RoleResource;
use App\Traits\HttpResponses;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\DBAL\Query\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    use HttpResponses;

    private UserService $userService;
    protected InviteService $inviteService;


    /**
     *
     * @param UserService $userService
     * @param InviteService $inviteService
     */

    public function __construct(UserService $userService, InviteService $inviteService)
    {
        $this->userService = $userService;
        $this->inviteService = $inviteService;
    }

    public function deleteUser($id)
    {
        $user = User::where('id', $id)->first();

        $deleteStatus = UserService::checkUserIdExists($id);

        if (!$deleteStatus) {
            return response()->json([
                'message' => 'User does not exist with given id'
            ], Response::HTTP_NOT_FOUND);
        }

        $roles = $user->roles()->pluck('role');

        if ($roles->contains('admin')) {

            return response()->json([
                'message' => 'Admin User cannot be deleted'
            ], 403);
        }

        if ($user->delete()) {

            return response()->json([
                'message' => 'User deleted Successfully'
            ], 200);
        }

        return response()->json([
            'message' => 'Oops Something Went Wrong'
        ], 403);
    }

    public function viewAllUsers()
    {
        $users = User::latest()->get();

        return response()->json([
            'total' => count($users),
            'users' => AdminResource::collection($users)
        ], 200);
    }

    public function viewUserRole(Request $request)
    {
        $role = User::find($request->id)->roles;

        return response()->json([
            'total' => count($role),
            'roles' => RoleResource::collection($role)
        ], 200);
    }



    /**
     * @param AssignRoleRequest $request
     * @return JsonResponse
     * @throws Exception
     * @throws ModelNotFoundException
     * @throws QueryException
     */

    public function assignRoles(AssignRoleRequest $request): JsonResponse
    {
        try {
            $roles = $this->userService->assignUserRole($request->validated());
            return $this->successResponse([RoleResource::collection($roles)], 'User is assigned the role ', Response::HTTP_OK);
        } catch (ModelNotFoundException $modelNotFoundException) {
            Log::error($modelNotFoundException->getMessage());
            return $this->errorResponse([], "User not Found", Response::HTTP_NOT_FOUND);
        } catch (QueryException $queryException) {
            Log::error($queryException->getMessage());
            return $this->errorResponse([], 'Cannot assign role to User', Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->errorResponse([], 'Something went wrong');
        }
    }



    /**
     *
     * @param MemberInviteRequest $request
     * @param InviteService $inviteService
     * @throws QueryException
     * @throws Exception
     * @return JsonResponse
     */

    public function inviteOthers(MemberInviteRequest $request): JsonResponse
    {
        $validatedInputs =  $request->validated();
        try {
            $token = $this->inviteService->generateToken();
            $this->inviteService->invite($validatedInputs, $token);
            if (config('app.env') === 'local' || config('app.env') === "development") {
                return $this->successResponse(['token' => $token], 'User invited successfully');
            }
            return $this->successResponse([], 'User invited successfully');
        } catch (QueryException $queryException) {
            Log::error($queryException->getMessage());
            return $this->errorResponse([], 'User could not be invited');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->errorResponse([], 'Something went wrong');
        }
    }

    public function updateUserStatus(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        try {
            if (!$user->activeStatus) {
                $user->activeStatus = true;
            } else {
                $user->activeStatus = false;
            }
            $user->save();
            $result = [
                'status' => 200,
                'message' => 'User status updated'
            ];
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }
        return response()->json($result, $result['status']);
    }
}
