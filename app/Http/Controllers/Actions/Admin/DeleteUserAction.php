<?php

namespace App\Http\Controllers\Actions\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use App\Traits\HttpResponses;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Mockery\Exception;
use Illuminate\Support\Facades\Log;


class DeleteUserAction extends Controller
{
    use HttpResponses;

    protected User $userModel;
    protected UserService $userService;

    /**
     *
     * @param User $userModel
     */
    public function __construct(User $userModel, UserService $userService)
    {
        $this->userModel = $userModel;
        $this->userService = $userService;
    }

    /**
     *
     * @param integer $id
     * @return JsonResponse
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function __invoke(int $id): JsonResponse
    {
        try {
            $user = $this->userModel->where('id', $id)->firstOrFail(); // Do this in UserService
            
            // $roles = $user->roles()->pluck('role');
            // if ($roles->contains('admin')) {
            //     return $this->errorResponse([], "Admin user cannot be deleted.", Response::HTTP_FORBIDDEN);
            // }
            $isAdmin = $this->userService->hasRole($user, 'admin');
            if(!$isAdmin){
                return $this->errorResponse([], "Admin user cannot be deleted.", Response::HTTP_FORBIDDEN);
            }
            return $this->successResponse([], "User deleted successfully.");

            // if ($user->delete()) {
            //     return $this->successResponse([], "User deleted successfully.");
            // }
            // return $this->errorResponse([], "Failed to delete user.");

        } catch (ModelNotFoundException $modelNotFoundException) {
            Log::error($modelNotFoundException->getMessage());
            return $this->errorResponse([], "User does not exists.", Response::HTTP_NOT_FOUND);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->errorResponse([], "An unexpected error occurred. Please try again later.");
        }
        
    }
}
