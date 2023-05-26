<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserStoreRequest;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Traits\HttpResponses;
use Mockery\Exception;

class AuthController extends Controller
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

    public function registerUser(UserStoreRequest $request)
    {
        try {
            $validatedUserRegister = $request->validated();
            $user = $this->userService->saveUserData($validatedUserRegister);
            $result = [
                'status' => 200,
                'user' => $user,
            ];
        } catch (Exception $e) {
            $result = [
                'status' => 403,
                'message' => $e->getMessage()
            ];
        }
        return response()->json($result, $result['status']);
    }

    public function loginUser(UserLoginRequest $request)
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

    public function logoutUser(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'User logged out sucessfully'
        ], 200);

    }

}
