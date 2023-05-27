<?php

namespace App\Http\Controllers\Actions\Auth;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Traits\HttpResponses;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VerifyPasswordTokenAction extends Controller
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
     *
     * @param string $token
     * @return JsonResponse
     * @throws Exception
     * @throws ModelNotFoundException
     * @throws NotFoundHttpException
     */
    public function __invoke(string $token): JsonResponse
    {
        try {
            $verified = $this->userService->checkIfTokenMatches($token);
            return $this->successResponse($verified, 'Token verified', Response::HTTP_OK);

        } catch (NotFoundHttpException $notFoundHttpException) {
            Log::error($notFoundHttpException->getMessage());
            return $this->errorResponse([], "Token does not exists", Response::HTTP_NOT_FOUND);
        } catch (ModelNotFoundException $modelNotFoundException) {
            Log::error($modelNotFoundException->getMessage());
            return $this->errorResponse([], "Token not found.", Response::HTTP_NOT_FOUND);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->errorResponse([], "An unexpected error occurred. Please try again later.");
        }
    }
}
