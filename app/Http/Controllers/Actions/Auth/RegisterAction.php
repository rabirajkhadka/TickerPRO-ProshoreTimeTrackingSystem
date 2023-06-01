<<<<<<< HEAD
=======
<?php

namespace App\Http\Controllers\Actions\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use App\Traits\HttpResponses;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
        } catch (ModelNotFoundException $modelNotFoundException) {
            Log::error($modelNotFoundException->getMessage());
            return $this->errorResponse([], "User not Invited", Response::HTTP_FORBIDDEN);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->errorResponse([], "Something Went Wrong");
        }
    }
}
>>>>>>> main
