<?php

namespace App\Http\Controllers\Actions\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HttpResponses;
use App\Services\UserService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;


class UpdateUserStatusAction extends Controller
{
    use HttpResponses;

    protected UserService $userService;
    
    /**
     * Undocumented function
     *
     * @param  UserService  $userService
     */

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    
    /**
     * Undocumented function
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $user = $this->userService->updateUserStatus($request->id);
            return $this->successResponse([], 'User status updated successfully.', Response::HTTP_OK);

        } catch (ModelNotFoundException $modelNotFoundException) {
            Log::error($modelNotFoundException->getMessage());
            return $this->errorResponse([], 'User with given id does not exists.', Response::HTTP_NOT_FOUND);
        } catch (AuthorizationException $authorizationException) {
            Log::error($authorizationException->getMessage());
            return $this->errorResponse([], 'Admin cannot disable itself.', Response::HTTP_FORBIDDEN);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->errorResponse([], 'Sorry! An error occured. Please try again later.');
        }
    }
}
