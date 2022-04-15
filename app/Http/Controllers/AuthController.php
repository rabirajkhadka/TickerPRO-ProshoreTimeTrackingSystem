<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordResetRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserStoreRequest;
use Illuminate\Http\Request;
use App\Services\UserService;
use Mockery\Exception;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /*
     * Register new users
     */
    public function registerUser(UserStoreRequest $request)
    {
        $user = UserService::saveUserData($request);
        $result = [
            'status' => 200,
            'user' => $user,
        ];
        return response()->json($result, $result['status']);
    }

    /*
     * Login using given creds
     */
    public function loginUser(UserLoginRequest $request)
    {
        try {
            $user = UserService::getUserWithCreds($request);
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

    public function forgotPass(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required | email'
        ]);
        $status = UserService::forgotPassword($request);
        if (!$status) {
            return response()->json([
                'message' => 'User with given email address not found'
            ], 404);
        }
        return response()->json([
            'message' => 'Reset email sent successfully'
        ], 200);
    }

    public function resetPass(PasswordResetRequest $request): JsonResponse
    {
        $status = UserService::resetPassword($request);
        if (!$status) {
            return response()->json([
                'message' => 'Could not reset password. Please check your token or email address'
            ], 404);
        }
        return response()->json([
            'message' => 'Password reset successfully'
        ], 200);
    }
}
