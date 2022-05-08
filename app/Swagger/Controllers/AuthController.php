<?php

namespace App\Swagger\Controllers;

use App\Http\Requests\PasswordResetRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserStoreRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * @OA\Post(path="/user/register",
     *     tags={"Auth"},
     *     summary="Register user after being invited",
     *     description="",
     *     operationId="registerUser",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Details of the user",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\Schema(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(response=500, description="Invalid token or invalid email address ")
     * )
     */
    public function registerUser(UserStoreRequest $request)
    {
    }

    /**
     * @OA\Post(path="/user/login",
     *     tags={"Auth"},
     *     summary="Login user",
     *     description="",
     *     operationId="loginUser",

     *     @OA\RequestBody(
     *         required=true,
     *         description="Login Details of the user",
     *
     *         @OA\JsonContent(ref="#/components/schemas/UserLoginBody")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\Schema(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(response=401, description="Email address or password is invalid ")
     * )
     */
    public function loginUser(UserLoginRequest $request)
    {
    }

    /**
     * @OA\Get(path="/user/logout",
     *     tags={"Auth"},
     *     summary="Logs out current logged in user session",
     *     description="",
     *     operationId="logoutUser",
     *     parameters={},
     *     @OA\Response(response="200", description="Successful operation"),
     *    @OA\Response(response=401, description="Unauthorized")

     * )
     */
    public function logoutUser(Request $request)
    {
    }

    /**
     * @OA\Post(path="/user/forgot-password",
     *     tags={"Auth"},
     *     summary="Forgot user password",
     *     description="",
     *     operationId="forgotPassword",

     *     @OA\RequestBody(
     *         required=true,
     *         description="Email address of the user",
     *
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *
     *     )
     * )
     */
    public function forgotPass(Request $request)
    {
    }


    /**
     * @OA\Post(path="/user/reset-password",
     *     tags={"Auth"},
     *     summary="Reset forgotten password",
     *     description="",
     *     operationId="resetPassword",

     *     @OA\RequestBody(
     *         required=true,
     *         description="Details of the user",
     *
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\Schema(ref="#/components/schemas/User")
     *     ),
     * )
     */
    public function resetPass(PasswordResetRequest $request)
    {
    }

}
