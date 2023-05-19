<?php

namespace App\Http\Controllers\Actions\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Services\UserService;
use App\Traits\HttpResponses;
use Doctrine\DBAL\Query\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mockery\Exception;
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
     * @throws ModelNotFoundException
     * @throws QueryException
     */
    public function __invoke(ForgotPasswordRequest $request): JsonResponse
    {
        $validatedForgetPass = $request->validated();
        try {
            // Check for a valid user
            $status = $this->userService->forgotPassword($validatedForgetPass);
            if(!$status) {
                return $this->errorResponse([], "Sorry! User with the given email address not found", Response::HTTP_NOT_FOUND); 
            }

            return $this->successResponse([], 'Reset email sent successfully', Response::HTTP_OK);

        } catch (ModelNotFoundException $modelNotFoundException) {
            Log::error($modelNotFoundException->getMessage());
            return $this->errorResponse([], "Sorry, we couldn't find an account associated with the provided email address.", Response::HTTP_NOT_FOUND);
        } catch (QueryException $queryException) {
            Log::error($queryException->getMessage());
            return $this->errorResponse([], "Oops! Something went wrong while processing your request. Please try again later.", Response::HTTP_BAD_REQUEST);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->errorResponse([], "Sorry, an unexpected error occurred. Please try again later.");
        }
    }

}
