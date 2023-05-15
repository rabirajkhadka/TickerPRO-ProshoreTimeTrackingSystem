<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\PasswordResetRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserStoreRequest;
use Illuminate\Http\Request;
use App\Services\UserService;
use Mockery\Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    private UserService $userService;

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
            $user = UserService::saveUserData($validatedUserRegister);
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
            $user = UserService::getUserWithCreds($validatedUserCreds);
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

    /**
     *
     * @param ForgotPasswordRequest $request
     * @return JsonResponse
     */
    public function forgotPass(ForgotPasswordRequest $request): JsonResponse
    {
        try {
            $validatedForgetPass = $request->validated();
            $status = $this->userService->forgotPassword($validatedForgetPass);
    
            if (!$status) {
                return response()->json([
                    'message' => 'User with the given email address not found'
                ], 404);
            }
    
            return response()->json([
                'message' => 'Reset email sent successfully'
            ], 200);
    
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     *
     * @param PasswordResetRequest $request
     * @return JsonResponse
     */
    public function resetPass(PasswordResetRequest $request): JsonResponse
    {
        try {
            $validatedResetPass = $request->validated();
            $status = $this->userService->resetPassword($validatedResetPass);
    
            if (!$status) {
                return response()->json([
                    'message' => 'Could not reset password. Please check your token or email address'
                ], 404);
            }
    
            return response()->json([
                'message' => 'Password reset successfully'
            ], 200);
    
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }
}
