<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Traits\HttpResponses;
use Mockery\Exception;

class AuthController extends Controller
{
    public function logoutUser(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'User logged out sucessfully'
        ], 200);
    }

}
