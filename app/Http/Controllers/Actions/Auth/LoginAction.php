<?php

namespace App\Http\Controllers\Actions\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordResetRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserStoreRequest;
use Illuminate\Http\Request;
use App\Services\UserService;
use Mockery\Exception;
use Illuminate\Http\JsonResponse;

class LoginAction extends Controller
{
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
            $validatedUserCreds = $request->validated();
            $user = $this->userService->getUserWithCreds($validatedUserCreds);
            $token = $user->createToken('auth_token');
            $result = [
                'status' => 200,
                'user' => $user,
                'access_token' => $token->plainTextToken,
                'token_type' => 'Bearer',
            ];
        } catch (Exception $e) {
            $result = [
                'status' => 401,
                'error' => $e->getMessage()
            ];
        }
        return response()->json($result, $result['status']);
    }
}
