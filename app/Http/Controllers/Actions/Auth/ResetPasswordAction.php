<?php

namespace App\Http\Controllers\Actions\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordResetRequest;
use App\Services\UserService;
use App\Traits\HttpResponses;
use Doctrine\DBAL\Query\QueryException;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

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
     * @param PasswordResetRequest $request
     * @return JsonResponse
     * @throws QueryException
     * @throws Exception
     */

    public function __invoke(PasswordResetRequest $request): JsonResponse
    {
        $validatedResetPass = $request->validated();
        try {
            $status = $this->userService->resetPassword($validatedResetPass);
            return $status === true ? $this->successResponse([], 'Password reset successfully') :
                $this->errorResponse([], 'The entered token is Invalid or Expired', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (QueryException $queryException) {
            Log::error($queryException->getMessage());
            return $this->errorResponse([], 'Failed to reset password. Please try again later.');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->errorResponse([], 'Something Went Wrong');
        }
    }
}
