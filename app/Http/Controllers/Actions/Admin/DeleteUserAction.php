<?php

namespace App\Http\Controllers\Actions\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use App\Traits\HttpResponses;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class DeleteUserAction extends Controller
{
    use HttpResponses;

    protected User $userModel;
    protected UserService $userService;

    /**
     *
     * @param User $userModel
     * @param UserService $userService
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
            if($this->userService->deleteUser($id)){
                return $this->successResponse([], "User deleted successfully.");
            }
            return $this->errorResponse([], "Admin user cannot be deleted.", Response::HTTP_FORBIDDEN);

        } catch (ModelNotFoundException $modelNotFoundException) {
            Log::error($modelNotFoundException->getMessage());
            return $this->errorResponse([], "User does not exists.", Response::HTTP_NOT_FOUND);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->errorResponse([], "An unexpected error occurred. Please try again later.");
        }
        
    }
}
