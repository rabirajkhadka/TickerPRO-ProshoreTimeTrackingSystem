<?php

namespace App\Http\Controllers\Actions\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UpdateUserStatusAction extends Controller
{
    protected User $userModel;
    protected UserService $userService;


    /**
     * @param User $userModel
     * @param UserService $userService
     */
    public function __construct(User $userModel, UserService $userService)
    {
        $this->userModel = $userModel;
        $this->userService = $userService;
    }


    /**
     * @param integer $id
     * @throws ModelNotFoundException
     * @throws Exception
     * @return JsonResponse
     */
    public function __invoke(int $id): JsonResponse
    {
        try {
            $status = $this->userService->updateUserStatus($id, Auth::id());
            return $status  === true ? $this->successResponse([], "User Status Successfully Updated") :
                $this->errorResponse([], "Admin cannot disable itself", Response::HTTP_FORBIDDEN);
        } catch (ModelNotFoundException $exception) {
            Log::error($exception->getMessage());
            return $this->errorResponse([], "User does not exists", Response::HTTP_NOT_FOUND);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->errorResponse([], "Something went wrong");
        }
    }
}
