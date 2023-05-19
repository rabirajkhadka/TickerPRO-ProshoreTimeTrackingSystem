<?php

namespace App\Http\Controllers\Actions\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordResetRequest;
use App\Services\UserService;
use App\Traits\HttpResponses;
use Doctrine\DBAL\Query\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mockery\Exception;
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
     *
     * @param PasswordResetRequest $request
     * @return JsonResponse
     * @throws Exception
     * @throws ModelNotFoundException
     * @throws QueryException
     */
    public function __invoke(PasswordResetRequest $request): JsonResponse
    {
        $validatedResetPass = $request->validated();
        try {
            // Check if the old pasword matches new password
            $checkOldPass = $this->userService->checkOldPass($validatedResetPass);
            if (!$checkOldPass){
                return $this->errorResponse([], "The new password cannot be the same as your old password. Please choose a different password.", Response::HTTP_BAD_REQUEST);
            }

            // Check for a valid token or the valid email address of user
            $status = $this->userService->resetPassword($validatedResetPass);
            if (!$status){
                return $this->errorResponse([], "Could not reset password. Please check your token or email address", Response::HTTP_FORBIDDEN);
            }
    
            return $this->successResponse([], 'Password reset successfully', Response::HTTP_OK);
    
        } catch (ModelNotFoundException $modelNotFoundException) {
            Log::error($modelNotFoundException->getMessage());
            return $this->errorResponse([], "Sorry, we couldn't find your account. Please make sure you entered the correct email address.", Response::HTTP_NOT_FOUND);
        }  catch (QueryException $queryException) {
            Log::error($queryException->getMessage());
            return $this->errorResponse([], "Oops! Something went wrong while processing your request. Please try again later.", Response::HTTP_BAD_REQUEST);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->errorResponse([], "Sorry, an unexpected error occurred. Please try again later.");
        }
    }
}
