<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\PasswordResetRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserStoreRequest;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Traits\HttpResponses;
use Doctrine\DBAL\Query\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mockery\Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    use HttpResponses;

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

    /**
     *
     * @param ForgotPasswordRequest $request
     * @return JsonResponse
     * @throws Exception
     * @throws ModelNotFoundException
     * @throws QueryException
     */
    public function forgotPass(ForgotPasswordRequest $request): JsonResponse
    {
        try {
            $validatedForgetPass = $request->validated();

            // Check for a valid user
            $status = $this->userService->forgotPassword($validatedForgetPass);
            if(!$status) {
                return $this->errorResponse([], "User with the given email address not found", Response::HTTP_NOT_FOUND); 
            }

            return $this->successResponse([], 'Reset email sent successfully', Response::HTTP_OK);

        } catch (ModelNotFoundException $modelNotFoundException) {
            Log::error($modelNotFoundException->getMessage());
            return $this->errorResponse([], $modelNotFoundException->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (QueryException $queryException) {
            Log::error($queryException->getMessage());
            return $this->errorResponse([], $queryException->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->errorResponse([], $exception->getMessage());
        }
    }

    /**
     *
     * @param PasswordResetRequest $request
     * @return JsonResponse
     * @throws Exception
     * @throws ModelNotFoundException
     * @throws QueryException
     */
    public function resetPass(PasswordResetRequest $request): JsonResponse
    {
        try {
            $validatedResetPass = $request->validated();

            // Check if the old pasword matches new password
            $checkOldPass = $this->userService->checkOldPass($validatedResetPass);
            if (!$checkOldPass){
                return $this->errorResponse([], "New password cannot be your old password", Response::HTTP_BAD_REQUEST);
            }

            // Check for a valid token or the valid email address of user
            $status = $this->userService->resetPassword($validatedResetPass);
            if (!$status){
                return $this->errorResponse([], "Could not reset password. Please check your token or email address", Response::HTTP_FORBIDDEN);
            }
    
            return $this->successResponse([], 'Password reset successfully', Response::HTTP_OK);
    
        } catch (ModelNotFoundException $modelNotFoundException) {
            Log::error($modelNotFoundException->getMessage());
            return $this->errorResponse([], $modelNotFoundException->getMessage(), Response::HTTP_NOT_FOUND);
        }  catch (QueryException $queryException) {
            Log::error($queryException->getMessage());
            return $this->errorResponse([], $queryException->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->errorResponse([], $exception->getMessage());
        }
    }
}
