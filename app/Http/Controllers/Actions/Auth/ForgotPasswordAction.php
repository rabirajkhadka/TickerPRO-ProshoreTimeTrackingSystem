<?php

namespace App\Http\Controllers\Actions\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Services\UserService;
use App\Traits\HttpResponses;
use Mockery\Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class ForgotPasswordAction extends Controller
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
     * @param ForgotPasswordRequest $request
     * @return JsonResponse
     * @throws Exception
     * @throws QueryException
     */
    public function __invoke(ForgotPasswordRequest $request): JsonResponse
    {
        $validatedForgetPass = $request->validated();
        try {
            $status = $this->userService->forgotPassword($validatedForgetPass);
            if(!$status) {
                return $this->errorResponse([], "Failed to send email. Please try again later."); 
            }
            return $this->successResponse([], 'Reset email sent successfully', Response::HTTP_OK);

        } catch (QueryException $queryException) {
            Log::error($queryException->getMessage());
            return $this->errorResponse([], "Something went wrong while processing your request. Please try again later.", Response::HTTP_BAD_REQUEST);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->errorResponse([], "An unexpected error occurred. Please try again later.");
        }
    }

}
