<?php

namespace App\Http\Controllers\Actions\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordResetRequest;
use App\Services\UserService;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class ResetPasswordAction extends Controller
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
     * Handle the incoming request.
     *
     * @param PasswordResetRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function __invoke(PasswordResetRequest $request): JsonResponse
    {
        $validatedResetPass = $request->validated();
        try {
            $status = $this->userService->resetPassword($validatedResetPass);
            if(!$status){
                return $this->errorResponse([], "Could not reset password. Please check your token or email address", Response::HTTP_FORBIDDEN);
            }
            return $this->successResponse([], 'Password reset successfully', Response::HTTP_OK);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->errorResponse([], "An unexpected error occurred. Please try again later.");
        }
    }
}
