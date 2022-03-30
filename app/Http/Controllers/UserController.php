<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;

class UserController extends Controller
{
    public function viewMe()
    {
        $user = Auth::guard('sanctum')->user();
        return response()->json([
            'user_details' => $user
        ]);
    }

    public function updateMe(Request $request)
    {
        $rules = [
            'email' => 'email | required',
            'password' => 'min:6|required_with:confirmPass|same:confirmPass',
            'confirmPass' => 'min:6',
        ];

        try {
            $user = UserService::getUser($request->toArray(), $rules);
            $user->password = $request['password'];
            $user->save();
            $result = [
                'status' => 200,
                'message' => 'User password updated',
                'user' => $user,
            ];

        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }
        return response()->json($result, $result['status']);
    }
}
