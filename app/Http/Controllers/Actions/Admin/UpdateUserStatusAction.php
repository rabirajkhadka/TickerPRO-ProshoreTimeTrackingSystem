<?php

namespace App\Http\Controllers\Actions\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Traits\HttpResponses;
use Doctrine\DBAL\Query\QueryException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use Mockery\Exception;


class UpdateUserStatusAction extends Controller
{
    use HttpResponses;
    protected $userService;

    /**
     *
     * @param  UserService  $userService
     * @var UserService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     *
     * @param  integer  $id
     * @return JsonResponse
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function __invoke(int $id): JsonResponse
    {
        try{
            $result = $this->userService->updateUserStatus($id);
           
            return $result ? $this->SuccessResponse([],"User status successfully updated") : 
            $this->errorResponse([], "Failed to update user status");

        }catch (ModelNotFoundException $modelNotFoundException) {
            Log::error($modelNotFoundException->getMessage());
            return $this->errorResponse([], "User not Found", Response::HTTP_NOT_FOUND);
        }catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->errorResponse([], 'Something went wrong. Please try again later.');
        }
    }

    
}

