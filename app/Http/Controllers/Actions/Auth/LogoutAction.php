<?php

namespace App\Http\Controllers\Actions\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\UserService;
use App\Traits\HttpResponses;
use Mockery\Exception;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

class LogoutAction extends Controller
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
     * Handle the incoming logout request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @return JsonResponse
     */

    public function __invoke(): JsonResponse
    {
        try {
            $this->userService->logout();
            return $this->successResponse([], 'Successfully Logged out');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->errorResponse([], 'Something went wrong');
        }
    }
}
