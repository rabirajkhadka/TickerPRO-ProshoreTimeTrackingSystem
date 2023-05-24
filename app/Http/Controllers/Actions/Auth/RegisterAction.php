<?php

namespace App\Http\Controllers\Actions\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use App\Traits\HttpResponses;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RegisterAction extends Controller
{
    use HttpResponses;

    protected UserService $userService;


    /**
     *
     * @param UserService $userService
     */

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @throws QueryException
     * @throws Exception
     * @return JsonResponse
     */
    public function __invoke(UserStoreRequest $request): JsonResponse
    {
        $validatedUserRegister = $request->validated();
        try {
            $user = $this->userService->saveUserData($validatedUserRegister);
            $data = new UserResource($user);
            return $this->successResponse([$data], "User Successfully Registered");
        } catch (QueryException $queryException) {
            Log::error($queryException->getMessage());
            return $this->errorResponse([], "Failed To Register User");
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->errorResponse([], "Something Went Wrong");
        }
    }
}
