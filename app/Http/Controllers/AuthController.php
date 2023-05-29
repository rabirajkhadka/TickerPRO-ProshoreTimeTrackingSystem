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
        $validatedForgetPass = $request->only('email');
        $status = UserService::forgotPassword($validatedForgetPass);
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
        $validatedResetPass = $request->safe()->all();
        $status = UserService::resetPassword($validatedResetPass);
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
