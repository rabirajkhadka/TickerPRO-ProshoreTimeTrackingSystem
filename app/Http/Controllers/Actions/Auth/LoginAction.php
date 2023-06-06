<?php

namespace App\Http\Controllers\Actions\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Services\UserService;
use App\Traits\HttpResponses;
use Mockery\Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
class LoginAction extends Controller
{
    use HttpResponses;

    protected UserService $userService;

    /**
    * @param UserService $userService
    */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(UserLoginRequest $request): JsonResponse
    {
        try {
            $validatedUser = $request->validated();
            $results = $this->userService->login($validatedUser);
            return $this->successResponse($results, 'Login Successful');
        } catch (ModelNotFoundException $modelNotFoundException) {
            Log::error($modelNotFoundException->getMessage());
            return $this->errorResponse([], 'User not Registered');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->errorResponse([],$exception->getMessage());
        }
    }
}
