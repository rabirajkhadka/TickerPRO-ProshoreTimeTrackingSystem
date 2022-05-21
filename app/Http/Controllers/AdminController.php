<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemberInviteRequest;
use App\Models\UserRole;
use App\Services\AdminService;
use App\Services\InviteService;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Models\User;
use Mockery\Exception;

class AdminController extends Controller
{
    public function deleteUser(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        if (!$user) {
            return response()->json([
                'message' => 'User does not exist with given id'
            ], 404);
        }
        AdminService::deleteRoles($request->id);
        $user->delete();
        return response()->json([
            'message' => 'User deleted successfully'
        ], 200);
    }

    public function viewAllUsers()
    {
        $users = User::all();

        return response()->json([
            'total' => count($users),
            'users' => $users
        ], 200);

    }

    public function assignRoles(Request $request)
    {
        $rules = [
            'email' => 'required | email',
        ];
        try {
            $user = UserService::getUser($request->toArray(), $rules);
            $role = UserRole::create([
                'user_id' => $user['id'],
                'role_id' => $request->role_id
            ]);
            $result = [
                'status' => 200,
                'message' => 'User role updated',
                'user' => $role,
            ];
        } catch (Exception $e) {
            $result = [
                'status' => 404,
                'error' => $e->getMessage()
            ];
        }
        return response()->json($result, $result['status']);

    }

    public function inviteOthers(MemberInviteRequest $request)
    {
        $validated = $request->safe()->only(['role_id', 'email', 'user_id', 'name']);
        $status = InviteService::invite($validated['name'], $validated['email'], $validated['role_id'], $validated['user_id']);

        if (!$status) {
            return response()->json([
                'message' => 'User could not be invited'
            ], 500);
        }

        return response()->json([
            'message' => 'User invited successfully'
        ], 200);
    }

}
