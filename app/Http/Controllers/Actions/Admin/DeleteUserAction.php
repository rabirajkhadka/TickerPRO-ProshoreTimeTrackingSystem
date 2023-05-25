<?php

namespace App\Http\Controllers\Actions\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
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

    /**
     *
     * @param User $userModel
     */
    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
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
            $user = $this->userModel->where('id', $id)->firstOrFail();
            
            $roles = $user->roles()->pluck('role');

            if ($roles->contains('admin')) {
                return $this->errorResponse([], "Admin user cannot be deleted.", Response::HTTP_FORBIDDEN);
            }
            if ($user->delete()) {
                return $this->successResponse([], "User deleted successfully.");
            }

        } catch (ModelNotFoundException $modelNotFoundException) {
            Log::error($modelNotFoundException->getMessage());
            return $this->errorResponse([], "User does not exists.", Response::HTTP_NOT_FOUND);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->errorResponse([], "An unexpected error occurred. Please try again later.");
        }
        
    }
}
