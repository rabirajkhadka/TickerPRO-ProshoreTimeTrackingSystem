<?php

namespace App\Http\Controllers\Actions\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordResetRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserStoreRequest;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Traits\HttpResponses;
use Mockery\Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
class LoginAction extends Controller
{
    use HttpResponses;

    protected UserService $userService;

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
    public function __invoke(UserLoginRequest $request)
    {
        try {
            $validatedUser = $request->validated();
            $user = $this->userService->getUserWithCreds($validatedUser);
            // dd($user);
            $token = $user->createToken('auth_token');
            $result = [
                'user' => $user,
                'access_token' => $token->plainTextToken,
                'token_type' => 'Bearer',
            ];
            return $this->successResponse($result, 'Login Successful');
        } catch (ModelNotFoundException $modelNotFoundException) {

            return $this->errorResponse([], 'User does not Exist');
        } catch (Exception $exception) {
            return $this->errorResponse([],'Something Went Wrong');
        }
        // return response()->json($result, $result['status']);
    }
}
